<?php

namespace Tests\Feature;

use App\Models\Promotion;
use App\Models\User;
use App\Models\garage;
use App\Models\Mechanic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class MechanicPromotionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $garage;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a garage
        $this->garage = Garage::create([
            'ref' => 'garage1',
            'name' => 'Test Garage',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'virtualGarage' => false, // or true based on logic
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'domaines' => json_encode(['Mechanical', 'Electrical']), // example
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


        // Create a user (mechanic) associated with the garage
        $this->user = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true,
        ]);

        // Create active promotions for the mechanic's garage
        Promotion::create([
            'nom_promotion' => 'Active Promo 1',
            'ville' => 'Paris',
            'date_debut' => now()->subDays(5),
            'date_fin' => now()->addDays(10),
            'lien_promotion' => 'https://example.com/active1',
            'garage_id' => $this->garage->id,
            'photo_promotion' => 'https://example.com/photo1.jpg',
            'description' => 'Active promotion 1',
        ]);


        // Create expired promotions for the mechanic's garage
        Promotion::create([
            'nom_promotion' => 'Expired Promo 1',
            'ville' => 'Paris',
            'date_debut' => now()->subDays(20),
            'date_fin' => now()->subDays(10),
            'lien_promotion' => 'https://example.com/expired1',
            'garage_id' => $this->garage->id,
            'photo_promotion' => 'https://example.com/photo3.jpg',
            'description' => 'Expired promotion 1',
        ]);
        // Authenticate as the user using the 'mechanic' guard
        $this->actingAs($this->user, 'mechanic');
    }

    /** @test */
    public function test_it_displays_active_and_expired_promotions_for_mechanics_garage()
    {
        // // Act as the mechanic and visit the promotions index
        // $this->actingAs($mechanic)
        //     ->get('fixi-pro/promotions/')
        //     ->assertStatus(302)
        //     ->assertViewHas('activePromotions')
        //     ->assertViewHas('expiredPromotions');
        $response = $this->get(route('mechanic.promotions.index'));
        $response->assertStatus(200)
            ->assertViewHas('activePromotions')
            ->assertViewHas('expiredPromotions');
    }
}
