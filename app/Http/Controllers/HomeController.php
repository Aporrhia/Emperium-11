<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Office;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch apartments (type = 'apartment', latest 4)
        $apartments = Apartment::latest()->take(4)->get();

        // Fetch offices (type = 'office', latest 4)
        $offices = Office::latest()->take(4)->get();

        return view('partials.home', compact('apartments', 'offices'));
    }
}