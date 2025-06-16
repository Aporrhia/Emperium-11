<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserStats;
use App\Models\Horse;
use App\Models\Race;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use Carbon\Carbon;

class RaceProposalTest extends TestCase
{
    use RefreshDatabase;

public function test_authenticated_user_can_place_bid_on_race()
    {
        $user = User::factory()->create();
        $stats = UserStats::factory()->create([
            'user_id' => $user->id,
            'balance' => 5000,
            'total_expenses' => 0,
        ]);
        $race = Race::factory()->create([
            'status' => 'upcoming',
            'start_time' => Carbon::now()->addHour(),
        ]);
        $horse = Horse::factory()->create();
        $race->horses()->attach($horse->id);

        $this->actingAs($user);

        $response = $this->post(route('races.bid', ['race' => $race->id]), [
            'horse_id' => $horse->id,
            'amount' => 1000,
        ]);

        $response->assertRedirect(route('races.show', $race->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bids', [
            'user_id' => $user->id,
            'race_id' => $race->id,
            'horse_id' => $horse->id,
            'amount' => 1000,
        ]);
    }

    public function test_user_cannot_bid_twice_on_same_race_and_horse()
    {
        $user = User::factory()->create();
        UserStats::factory()->create([
            'user_id' => $user->id,
            'balance' => 5000,
            'total_expenses' => 0,
        ]);
        $race = Race::factory()->create([
            'status' => 'upcoming',
            'start_time' => Carbon::now()->addHour(),
        ]);
        $horse = Horse::factory()->create();
        $race->horses()->attach($horse->id);

        $user->bids()->create([
            'race_id' => $race->id,
            'horse_id' => $horse->id,
            'amount' => 500,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('races.bid', ['race' => $race->id]), [
            'horse_id' => $horse->id,
            'amount' => 1000,
        ]);

        $response->assertRedirect(route('races.show', $race->id));
        $response->assertSessionHas('error');
    }

    public function test_guest_cannot_place_bid()
    {
        $race = Race::factory()->create([
            'status' => 'upcoming',
            'start_time' => Carbon::now()->addHour(),
        ]);
        $horse = Horse::factory()->create();
        $race->horses()->attach($horse->id);

        $response = $this->post(route('races.bid', ['race' => $race->id]), [
            'horse_id' => $horse->id,
            'amount' => 1000,
        ]);

        $response->assertRedirect(route('login'));
    }
}