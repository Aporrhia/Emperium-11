<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\House;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function properties(Request $request)
    {
        $apartmentsQuery = Apartment::query();
        $housesQuery = House::query();

        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $apartmentsQuery->where('title', 'like', $searchTerm)
                           ->orWhere('location', 'like', $searchTerm);
            $housesQuery->where('title', 'like', $searchTerm)
                       ->orWhere('location', 'like', $searchTerm);
        }

        if ($request->has('type')) {
            $apartmentsQuery->where('type', $request->type);
            $housesQuery->where('type', $request->type);
        }

        $apartments = $apartmentsQuery->get();
        $houses = $housesQuery->get();
        $properties = $apartments->merge($houses)->sortBy('title');

        // Paginate the merged collection
        $perPage = 10;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;
        $paginatedProperties = $properties->slice($offset, $perPage);
        $properties = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedProperties,
            $properties->count(),
            $perPage,
            $page,
            ['path' => route('properties')]
        );

        return view('properties.properties', compact('properties'));
    }

    public function show($id)
    {
        $property = Apartment::find($id) ?? House::find($id);
        if (!$property) {
            abort(404, 'Property not found');
        }

        return view('properties.show', compact('property'));
    }
}