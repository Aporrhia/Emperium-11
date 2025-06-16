<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertySellTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_sell_property_to_emperium()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user);

        // Ensure user_stats exists for this user
        \DB::table('user_stats')->insert([
            'user_id' => $user->id,
            'balance' => 0,
            'total_income' => 0,
            'total_expenses' => 0,
        ]);

        $response = $this->post(route('properties.store'), [
            'type' => 'house',
            'title' => 'Test House',
            'address' => '123 Main St',
            'size' => 100,
            'price' => 100000,
            'latitude' => 100,
            'longitude' => 100,
            'sale_method' => 'emperium',
            'images' => [UploadedFile::fake()->image('house.jpg')],
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('sold_properties', [
            'title' => 'Test House',
            'sale_method' => 'emperium',
            'seller_type' => 'emperium',
            'is_active' => 1,
        ]);
    }

    public function test_user_can_list_property_for_other_users()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user);

        // Ensure user_stats exists for this user
        \DB::table('user_stats')->insert([
            'user_id' => $user->id,
            'balance' => 0,
            'total_income' => 0,
            'total_expenses' => 0,
        ]);

        $response = $this->post(route('properties.store'), [
            'type' => 'apartment',
            'title' => 'Test Apartment',
            'address' => '456 Main St',
            'size' => 80,
            'price' => 80000,
            'latitude' => 200,
            'longitude' => 200,
            'sale_method' => 'users',
            'images' => [UploadedFile::fake()->image('apartment.jpg')],
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('sold_properties', [
            'title' => 'Test Apartment',
            'sale_method' => 'users',
            'seller_type' => 'user',
            'is_active' => 1,
        ]);
    }

    public function test_validation_fails_with_missing_fields()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('properties.store'), [
            // Missing required fields
        ]);

        $response->assertSessionHasErrors([
            'type', 'title', 'address', 'size', 'price', 'latitude', 'longitude', 'sale_method'
        ]);
    }
}