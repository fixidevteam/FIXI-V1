<?php

namespace Tests\Feature;

use App\Models\garage;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_retrieves_promotions_based_on_user_ville_and_date_fin()
    {
        $user = User::factory()->create(['ville' => 'Paris', 'status' => true]);

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

        $promotion = Promotion::create([
            'nom_promotion' => 'promotion',
            'ville' => 'fes',
            'date_debut' => now(),
            'date_fin' => '2026-01-01',
            'lien_promotion' => "https://web.whatsapp.com/",
            'garage_id' => $garage->id,
            'photo_promotion' => 'https://web.whatsapp.com/',
            'description' => 'test',
        ]);

        $this->actingAs($user)
            ->get('fixi-plus/dashboard') // Assuming your route is named 'dashboard.index'
            ->assertStatus(200)
            ->assertViewHas('promotions');
    }

    
}
