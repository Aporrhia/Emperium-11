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
use Illuminate\Support\Facades\DB;

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

    public function sell()
    {
        return view('properties.sell');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:house,apartment',
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'size' => 'required|integer|min:1',
            'price' => 'required|numeric|min:1',
            'latitude' => 'required|numeric|between:0,2000',
            'longitude' => 'required|numeric|between:0,2000',
            'sale_method' => 'required|in:users,emperium',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB per image
        ]);


        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $imagePaths[] = $path;
            }
        }

        $price = $request->input('price');
        $saleMethod = $request->input('sale_method');
        $user = Auth::user();

        if ($saleMethod === 'emperium') {
            // Selling to Emperium 11: Immediate sale
            $emperiumPrice = (int)$price * 0.4; // 40% of estimated market value

            // Credit the user with the Emperium 11 price
            DB::table('user_stats')->where('user_id', $user->id)->increment('balance', $emperiumPrice);
            DB::table('user_stats')->where('user_id', $user->id)->increment('total_income', $emperiumPrice);

            $randCoeff = rand(12, 18) / 10;
            (int)$price = (int)$price * $randCoeff;

            // List the property for sale again, with Emperium 11 as the seller
            DB::table('sold_properties')->insert([
                'title' => $request->input('title'),
                'type' => $request->input('type'),
                'address' => $request->input('address'),
                'size' => $request->input('size'),
                'price' => $price,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'sale_method' => $saleMethod,
                'user_id' => $user->id, 
                'seller_type' => 'emperium',
                'seller_id' => null,
                'is_active' => true, 
                'images' => json_encode($imagePaths),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('home')->with('success', 'Property sold to Emperium 11 for $' . number_format($emperiumPrice, 2) . ' and is now listed for sale!');
        } else {
            // Selling to other users: List the property
            DB::table('sold_properties')->insert([
                'title' => $request->input('title'),
                'type' => $request->input('type'),
                'address' => $request->input('address'),
                'size' => $request->input('size'),
                'price' => $price,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'sale_method' => $saleMethod,
                'user_id' => $user->id,
                'seller_type' => 'user',
                'seller_id' => $user->id, // Seller is the listing user
                'is_active' => true,
                'images' => json_encode($imagePaths),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('home')->with('success', 'Property listed for sale to other users!');
        }
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
        $propertiesQuery = DB::table('sold_properties')
            ->where('is_active', true)
            ->select(
                DB::raw('id'),
                DB::raw('type as type'),
                'title',
                DB::raw('address as location'),
                'price',
                'size',
                'seller_type',
                'seller_id',
                'images'
        );
        
        $queries[] = $propertiesQuery;

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


        if ($request->filter_type === 'house' || $request->filter_type === 'apartment') {
            if ($request->has('price_category') && $request->price_category !== 'all') {
                $priceCategory = $request->price_category;
    
                foreach ($queries as $query) {
                    if ($priceCategory === 'low-end') {
                        $query->where('price', '<', 99999);
                    } elseif ($priceCategory === 'medium') {
                        $query->whereBetween('price', [100000, 299999]);
                    } elseif ($priceCategory === 'high-end') {
                        $query->where('price', '>', 300000);
                    }
                }
            }
        }

        // Execute queries and combine results
        $properties = collect();
        foreach ($queries as $query) {
            $properties = $properties->concat($query->get());
        }

        // Apply sorting (if provided in URL or session)
        $sortOption = $request->input('sort', session('sort_option', 'title')); // Default to 'title'

        // Save the sort option in the session if it was provided in the URL
        if ($request->has('sort')) {
            session(['sort_option' => $sortOption]);
        }

        if ($sortOption === 'price-low-to-high') {
            $properties = $properties->sortBy('price');
        } elseif ($sortOption === 'price-high-to-low') {
            $properties = $properties->sortByDesc('price');
        } else {
            $properties = $properties->sortBy('title');
        }

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
        
        // Check if the purchase is from the sold_properties (properties) table
        if (in_array($type, ['house', 'apartment'])) {
            $property = DB::table('sold_properties')
                ->where('id', $id)
                ->where('is_active', true)
                ->where('type', $type)
                ->first();

            if ($property) {
                // Buying from the sold_properties table
                $price = $property->price;

                // Check if the user has enough balance
                if ($user->stats->balance < $price) {
                    return redirect()->back()->with('error', 'Insufficient balance to purchase this property.');
                }

                // Deduct the price from the buyer's balance
                $user->stats()->update([
                    'balance' => $user->stats->balance - $price,
                    'total_expenses' => $user->stats->total_expenses + $price,
                ]);

                // Credit the seller if the seller is a user
                if ($property->seller_type === 'user' && $property->seller_id) {
                    DB::table('user_stats')->where('id', $property->seller_id)->increment('balance', $price);
                    DB::table('user_stats')->where('id', $property->seller_id)->increment('total_income', $price);
                }
                // If seller_type is 'emperium', the money goes to Emperium 11 (no action needed)

                // Mark the property as sold
                DB::table('sold_properties')->where('id', $id)->update([
                    'is_active' => false,
                    'updated_at' => now(),
                ]);

                // Create an ownership record
                $user->ownedProperties()->create([
                    'ownable_id' => $property->id,
                    'ownable_type' => 'App\Models\SoldProperty', // Adjust based on your actual model name
                ]);

                return redirect()->route('home')->with('success', 'Property purchased successfully!');
            }
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

        if ($user->stats->balance < $property->price) {
            return redirect()->back()->with('error', 'Insufficient balance to purchase this property.');
        }

        if (!$property) {
            abort(404, 'Property not found');
        }

        // Create an ownership record
        $user = auth()->user();
        $user->ownedProperties()->create([
            'ownable_id' => $property->id,
            'ownable_type' => get_class($property),
        ]);

        // Update user stats (example: deduct price from balance)
        $user->stats()->update([
            'balance' => $user->stats->balance - $property->price,
            'total_expenses' => $user->stats->total_expenses + $property->price,
        ]);

        return redirect()->route('home')->with('success', 'Property purchased successfully!');
    }
}