<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TierListController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->whereHas('privacySetting', function ($query) {
                $query->where('show_in_tier_list', true);
            })
            ->with(['stats', 'privacySetting'])
            ->get()
            ->sortByDesc(function ($user) {
                return $user->stats ? $user->stats->total_income : 0;
            })
            ->values();

        return view('partials.tier-list', compact('users'));
    }
}