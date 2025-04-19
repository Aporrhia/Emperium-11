<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\House;
use App\Models\Office;
use App\Models\Bunker;
use App\Models\Warehouse;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    /**
     * Display the properties (apartments and houses).
     */
    public function properties(Request $request)
    {
        $queries = [
            Apartment::query(),
            House::query(),
        ];

        $types = ['apartment', 'house'];

        return $this->displayProperties($request, $queries, $types, 'Properties for Rent');
    }

    /**
     * Display the businesses (offices, bunkers, warehouses, facilities).
     */
    public function businesses(Request $request)
    {
        $queries = [
            Office::query(),
            Bunker::query(),
            Warehouse::query(),
            Facility::query(),
        ];

        $types = ['office', 'bunker', 'warehouse', 'facility'];

        return $this->displayProperties($request, $queries, $types, 'Businesses for Rent');
    }

    /**
     * Common logic to display properties or businesses.
     */
    private function displayProperties(Request $request, array $queries, array $types, string $title)
    {
        // Apply search filter to all queries (if provided)
        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            foreach ($queries as $query) {
                $query->where('title', 'like', $searchTerm)
                      ->orWhere('location', 'like', $searchTerm);
            }
        }

        // Apply type filter (if provided)
        if ($request->has('filter_type') && $request->filter_type !== 'all') {
            $filterType = $request->filter_type;
            foreach ($queries as $query) {
                $query->where('type', $filterType);
            }
        }

        // Execute queries and combine results
        $properties = collect();
        foreach ($queries as $query) {
            $properties = $properties->concat($query->get());
        }

        // Sort by title
        $properties = $properties->sortBy('title');

        return view('properties.properties', compact('properties', 'types', 'title'));
    }

    /**
     * Display a single property/business details.
     */
    public function show($id, $type)
    {
        $property = null;

        // Fetch the property based on type
        if ($type === 'apartment') {
            $property = Apartment::findOrFail($id);
        } elseif ($type === 'house') {
            $property = House::findOrFail($id);
        } elseif ($type === 'office') {
            $property = Office::findOrFail($id);
        } elseif ($type === 'bunker') {
            $property = Bunker::findOrFail($id);
        } elseif ($type === 'warehouse') {
            $property = Warehouse::findOrFail($id);
        } elseif ($type === 'facility') {
            $property = Facility::findOrFail($id);
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
        } elseif ($type === 'office') {
            $property = Office::findOrFail($id);
        } elseif ($type === 'bunker') {
            $property = Bunker::findOrFail($id);
        } elseif ($type === 'warehouse') {
            $property = Warehouse::findOrFail($id);
        } elseif ($type === 'facility') {
            $property = Facility::findOrFail($id);
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