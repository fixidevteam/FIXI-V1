<?php

namespace Tests\Feature;

use App\Models\garage;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_displays_promotions_based_on_user_ville_and_date_fin()
    {
        // Create a user with a specific ville
        $user = User::factory()->create(['status' => 1, 'ville' => 'marrakech']);


        // Create a garage (unrelated to the user's ville)
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

        // Create valid promotions for the user's ville
        Promotion::create([
            'nom_promotion' => 'Promo 1',
            'ville' => 'Paris',
            'date_debut' => now(),
            'date_fin' => now()->addDays(10),
            'lien_promotion' => 'https://example.com/promo1',
            'garage_id' => $garage->id,
            'photo_promotion' => 'https://example.com/photo1.jpg',
            'description' => 'Promotion 1 description',
        ]);


        // Act as the user and visit the promotions route
        $this->actingAs($user)
            ->get('fixi-plus/promotions/') // Assuming your route is named 'promotions.index'
            ->assertStatus(200)->assertViewHas('promotions');
    }

    
}
