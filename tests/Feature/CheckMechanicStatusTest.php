<?php

namespace Tests\Feature\Middleware;

use App\Models\garage;
use App\Models\Mechanic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckMechanicStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_inactive_mechanic_is_logged_out_and_redirected_to_mechanic_login()
    {
        $garage = garage::create([
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

        // Create a mechanic associated with the garage
        $mechanic = Mechanic::create([
            'name' => 'Test Mechanic',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'status' => false,
            'garage_id' => $garage->id, // Mechanic is linked to the garage
        ]);
        $this->actingAs($mechanic, 'mechanic')
            ->get('fixi-pro/operations')
            ->assertSessionHasErrors([
                'email' => 'Votre compte est inactif. Veuillez contacter l\'administrateur.',
            ]);

        $this->assertGuest('mechanic');
    }

    /** @test */
}
