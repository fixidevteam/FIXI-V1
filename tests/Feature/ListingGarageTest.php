<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListingGarageTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_search_filters_garages_correctly()
    {
        // Create test data for users, villes, and garages
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'usertest07gmail.com',
            'password' => bcrypt('password'),
            'ville' => 'Marrakech', // Default city for the user
            'status' => 1,
        ]);

        $ville1 = \App\Models\Ville::create(['ville' => 'Marrakech']);
        $ville2 = \App\Models\Ville::create(['ville' => 'VilleB']);

        // Create garages with specific villes and user_id
        $garage1 = \App\Models\Garage::create([
            'ref' => 'garage111',
            'name' => 'Test Garage',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'virtualGarage' => false,
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'domaines' => json_encode(['Mechanical', 'Electrical']),
            'confirmation' => 'automatique',
            'presentation' => 'A reliable garage with quick service.',
            'telephone' => '0612345678',
            'fixe' => '0522345678',
            'whatsapp' => '0612345678',
            'instagram' => 'https://instagram.com/testgarage',
            'facebook' => 'https://facebook.com/testgarage',
            'tiktok' => 'https://tiktok.com/@testgarage',
            'linkedin' => 'https://linkedin.com/company/testgarage',
            'latitude' => 33.5731,
            'longitude' => -7.5898,
        ]);
        $garage2 = \App\Models\Garage::create([
            'ref' => 'garage1',
            'name' => 'Test Garage',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'virtualGarage' => false,
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'domaines' => json_encode(['Mechanical', 'Electrical']),
            'confirmation' => 'automatique',
            'presentation' => 'A reliable garage with quick service.',
            'telephone' => '0612345678',
            'fixe' => '0522345678',
            'whatsapp' => '0612345678',
            'instagram' => 'https://instagram.com/testgarage',
            'facebook' => 'https://facebook.com/testgarage',
            'tiktok' => 'https://tiktok.com/@testgarage',
            'linkedin' => 'https://linkedin.com/company/testgarage',
            'latitude' => 33.5731,
            'longitude' => -7.5898,
        ]);
        $garage3 = \App\Models\Garage::create([
            'ref' => 'garage33',
            'name' => 'Test Garage',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'virtualGarage' => false,
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'domaines' => json_encode(['Mechanical', 'Electrical']),
            'confirmation' => 'automatique',
            'presentation' => 'A reliable garage with quick service.',
            'telephone' => '0612345678',
            'fixe' => '0522345678',
            'whatsapp' => '0612345678',
            'instagram' => 'https://instagram.com/testgarage',
            'facebook' => 'https://facebook.com/testgarage',
            'tiktok' => 'https://tiktok.com/@testgarage',
            'linkedin' => 'https://linkedin.com/company/testgarage',
            'latitude' => 33.5731,
            'longitude' => -7.5898,
        ]);

        // Simulate authenticated user
        $this->actingAs($user);

        // Make a GET request with the search parameter 'ville'
        $response = $this->get(route('garages.index', ['ville' => 'Marrakech']));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert 'garages' contains only the correct filtered garage
        $response->assertViewHas('garages', function ($garages) use ($garage1) {
            return $garages->total() === 1 && $garages->first()->id === $garage1->id;
        });

        // Assert 'searchVille' is passed correctly to the view
        $response->assertViewHas('searchVille', 'Marrakech');
    }
}
