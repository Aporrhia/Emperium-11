<?php

namespace App\Http\Controllers;

use App\Models\Race;
use App\Models\Horse;
use App\Models\Bid;
use App\Models\RaceResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class RaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        $upcomingRaces = Race::where('status', 'upcoming')
            ->where('start_time', '>', Carbon::now())
            ->with(['horses', 'bids.horse'])
            ->orderBy('start_time')
            ->get();

        $runningRaces = Race::where('status', 'running')
            ->where('start_time', '<=', Carbon::now())
            ->with(['horses', 'bids.horse'])
            ->orderBy('start_time')
            ->get();

        $finishedRaces = Race::where('status', 'finished')
            ->with(['horses', 'raceResults', 'bids.horse'])
            ->orderBy('start_time', 'desc')
            ->take(10)
            ->get();

        $horses = Horse::with('raceResults')->get();

        $userBids = Auth::check()
            ? Auth::user()->bids()->whereIn('race_id', $upcomingRaces->pluck('id'))->get()->groupBy('race_id')
            : collect();

        return view('races.races', compact(
            'upcomingRaces', 
            'runningRaces', 
            'finishedRaces',
            'horses', 
            'userBids'
        ));
    }

    public function show(Race $race)
    {
        $race->load(['horses', 'bids.horse', 'raceResults']);
        $userBalance = Auth::check() ? Auth::user()->stats->balance : null;
        return view('races.show', compact('race', 'userBalance'));
    }

    public function updateStatuses(Request $request)
    {
        $races = Race::where('status', '!=', 'finished')->get();

        if ($races->isEmpty()) {
            $this->RaceCreator();
            return redirect()->route('races')->with('info', 'No race statuses needed updating.');
        }
        $updated = false;

        foreach ($races as $race) {
            // Check if race should transition from "upcoming" to "running"
            if ($race->start_time <= Carbon::now() && $race->status === 'upcoming') {
                $race->status = 'running';
                $race->save();
                $updated = true;
            }

            // Check if race should transition from "running" to "finished"
            if ($race->status === 'running') {
                $endTime = $race->start_time->copy()->addMinutes((int) round($race->duration));
                if (Carbon::now()->greaterThanOrEqualTo($endTime)) {
                    $this->simulateRaceInternal($race);
                    $updated = true;
                }
            }
        }

        if ($updated) {
            return redirect()->route('races')->with('success', 'Race statuses updated successfully!');
        }

        return redirect()->route('races')->with('info', 'No race statuses needed updating.');
    }

    public function placeBid(Request $request, Race $race)
    {
        if ($race->status !== 'upcoming' || $race->start_time <= Carbon::now()) {
            return redirect()->route('races.show', $race)->with('error', 'Bidding is closed for this race.');
        }

        $user = Auth::user();

        $horseId = $request->horse_id;
        $amount = $request->amount;

        if (!$user->stats) {
            Stats::create([
                'user_id' => $user->id,
                'balance' => 0,
                'total_expenses' => 0,
            ]);
            $user->refresh();
        }

        // Check if user has already bid on this race
        $existingBid = $user->bids()
            ->where('race_id', $race->id)
            ->where('horse_id', $horseId)
            ->first();

        if ($existingBid) {
            return redirect()->route('races.show', $race)->with('error', 'You have already placed a bid on this race.');
        }

        $userBalance = $user->stats->balance ?? 0;
        if ($userBalance < $amount) {
            return redirect()->route('races.show', $race)->with('error', 'Insufficient balance to place this bid.');
        }

        DB::transaction(function () use ($user, $race, $horseId, $amount) {
            // Create the bid
            $user->bids()->create([
                'race_id' => $race->id,
                'horse_id' => $horseId,
                'amount' => $amount,
            ]);

            $user->stats()->update([
                'balance' => $user->stats->balance - $amount,
                'total_expenses' => $user->stats->total_expenses + $amount,
            ]);
        });
        

        return redirect()->route('races.show', $race)->with('success', 'Bid placed successfully!');
    }

    public function simulateRace(Race $race)
    {
        if ($race->status !== 'upcoming' || $race->start_time > Carbon::now()) {
            return redirect()->route('races.show', $race)->with('error', 'This race cannot be simulated yet.');
        }

        $this->simulateRaceInternal($race);

        return redirect()->route('races.show', $race)->with('success', 'Race simulated and results recorded!');
    }

    protected function simulateRaceInternal(Race $race)
    {
        if ($race->status === 'upcoming') {
            $race->status = 'running';
            $race->save();
        }

        $horses = $race->horses;
        $results = [];

        foreach ($horses as $horse) {
            $baseTime = $race->distance / $horse->speed;
            $randomFactor = rand(70, 120) / 100;
            $finishTime = $baseTime * $randomFactor;
            $results[$horse->id] = $finishTime;
        }

        asort($results);
        $position = 1;

        foreach ($results as $horseId => $finishTime) {
            RaceResult::create([
                'race_id' => $race->id,
                'horse_id' => $horseId,
                'position' => $position,
            ]);
            $position++;
        }

        $race->status = 'finished';
        $race->save();

        $this->distributeWinnings($race);
        $this->createNewRaceWithRandomInterval();
    }

    protected function distributeWinnings(Race $race)
    {
        $winner = $race->raceResults()->where('position', 1)->first();
        if (!$winner) {
            return;
        }

        $winningHorseId = $winner->horse_id;

        // Get all bids for this race
        $bids = $race->bids;
        $totalPool = $bids->sum('amount');
        $winningBids = $bids->where('horse_id', $winningHorseId);
        $totalWinningBidAmount = $winningBids->sum('amount');

        if ($totalWinningBidAmount == 0) {
            return; // No winning bids
        }

        // Take a 10% commission
        $commissionRate = 0.10;
        $commission = $totalPool * $commissionRate;
        $payoutPool = $totalPool - $commission;
        $payoutPerDollar = round($payoutPool / $totalWinningBidAmount, 2);
        if ($payoutPerDollar <= 1) {
            $payoutPerDollar = 1.1;
        }

        // Distribute winnings
        foreach ($winningBids as $bid) {
            $payout = $bid->amount * $payoutPerDollar;
            $bid->payout = round($payout, 2);
            $bid->save();
            
            $user = $bid->user;
            if ($user->stats) {
                $user->stats->increment('balance', $bid->payout);
                $user->stats->increment('total_income', $bid->payout);
            }
        }
    }

    protected function createNewRaceWithRandomInterval()
    {
        $upcomingRaceCount = Race::where('status', 'upcoming')
        ->where('start_time', '>', Carbon::now())
        ->count();

        if ($upcomingRaceCount >= 5) {
            return;
        }

        $availableSlots = 5 - $upcomingRaceCount;
        $raceCount = min(rand(1, 3), $availableSlots);

        $raceCount = rand(1, 3);
        for ($i = 0; $i < $raceCount; $i++) {
            $this->RaceCreator();
        }
    }

    public function RaceCreator()
    {
        $distasnces = [1000, 1250, 1500, 2000, 2500];

        // Random interval between 5 minutes (300 seconds) and 15 minutes (900 seconds)
        $randomSeconds = rand(300, 900);
        $timeFactor = rand(15, 20) * 0.01;
        $distance = Arr::random($distasnces, 1);
        $duration = round(($distance[0] / 70) * $timeFactor, 1);
        $duration = min($duration, 4);
        $startTime = Carbon::now()->addSeconds($randomSeconds);

        $race = Race::create([
            'start_time' => $startTime,
            'status' => 'upcoming',
            'distance' => $distance[0],
            'duration' => $duration,
        ]);

        $horses = Horse::all();
        $race->horses()->attach($horses->pluck('id'));
    }

    public function createRace()
    {
        $this->RaceCreator();

        return redirect()->route('races')->with('success', 'New race created!');
    }
}