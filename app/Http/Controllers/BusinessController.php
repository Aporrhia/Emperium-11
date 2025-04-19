<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Warehouse;
use App\Models\Bunker;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    public function businesses(Request $request)
    {
        $officesQuery = Office::query();
        $bunkersQuery = Bunker::query();
        $warehousesQuery = Warehouse::query();
        $facilitiesQuery = Facility::query();

        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $apartmentsQuery->where('title', 'like', $searchTerm)
                           ->orWhere('location', 'like', $searchTerm);
            $housesQuery->where('title', 'like', $searchTerm)
                       ->orWhere('location', 'like', $searchTerm);
        }

        // Apply type filter (if provided)
        if ($request->has('filter_type') && $request->filter_type !== 'all') {
            $filterType = $request->filter_type;
            $apartmentsQuery->where('type', $filterType);
            $housesQuery->where('type', $filterType);
        }

        $apartments = $apartmentsQuery->get();
        $houses = $housesQuery->get();
        $properties = $apartments->concat($houses)->sortBy('title');

        $types = ['apartment', 'house'];

        return view('properties.bu', compact('properties', 'types'));
    }

    public function show_properties($id, $type)
    {
        $property = null;
    
        if ($type === 'apartment') {
            $property = Apartment::findOrFail($id);
        } elseif ($type === 'house') {
            $property = House::findOrFail($id);
        }
    
        if (!$property) {
            abort(404, 'Property not found');
        }
    
        return view('properties.show', compact('property'));
    }

    public function buy($id, Request $request)
    {
        $type = $request->input('type');
        $property = null;
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You need to be logged in to purchase a property.');
        }
        

        if ($type === 'apartment') {
            $property = Apartment::findOrFail($id);
        } elseif ($type === 'house') {
            $property = House::findOrFail($id);
        }

        // if ($user->stats->balance < $property->price) {
        //     return redirect()->back()->with('error', 'Insufficient balance to purchase this property.');
        // }

        if (!$property) {
            abort(404, 'Property not found');
        }

        // // Create an ownership record
        // $user = auth()->user();
        // $user->ownedProperties()->create([
        //     'ownable_id' => $property->id,
        //     'ownable_type' => get_class($property),
        // ]);

        // // Update user stats (example: deduct price from balance)
        // $user->stats()->update([
        //     'balance' => $user->stats->balance - $property->price,
        //     'total_expenses' => $user->stats->total_expenses + $property->price,
        // ]);

        return redirect()->route('properties')->with('success', 'Property purchased successfully!');
    }
}