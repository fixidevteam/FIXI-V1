<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Garage;
use App\Models\Voiture;
use App\Models\Operation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GetAnalyticsDataControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'fes',
            'status' => true
        ]);

        // Create a garage for the user
        $this->garage = Garage::create([
            'name' => 'Test Garage',
            'ref' => 'garage1',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'user_id' => $this->user->id,
            'virtualGarage' => false,
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'confirmation' => 'automatique',
        ]);


        // Authenticate as the user
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_returns_analytics_data_for_garage()
    {
        // Create a voiture
        $voiture = Voiture::create([
            'user_id' => $this->user->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
            'photo' => 'photo.jpg',
        ]);

        // Create operations for the garage
        $operation1 = Operation::create([
            'garage_id' => $this->garage->id,
            'voiture_id' => $voiture->id,
            'date_operation' => Carbon::now()->subMonths(2)->format('Y-m-d'),
            'description' => 'Oil Change',
            'categorie' => 'Maintenance',
        ]);

        $operation2 = Operation::create([
            'garage_id' => $this->garage->id,
            'voiture_id' => $voiture->id,
            'date_operation' => Carbon::now()->subMonths(1)->format('Y-m-d'),
            'description' => 'Tire Rotation',
            'categorie' => 'Maintenance',
        ]);

        // Call the API endpoint
        $response = $this->getJson(route('analytics.data'));

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'operations',
                'clients',
            ]);

        // Verify the operations data
        $operations = $response->json('operations');
        $this->assertCount(2, $operations); // Two months of data

        // Verify the clients data
        $clients = $response->json('clients');
        $this->assertCount(2, $clients); // Two months of data
    }

    /** @test */
    public function it_returns_empty_data_if_no_garage_is_associated()
    {

        // Call the API endpoint
        $response = $this->getJson(route('analytics.data'));

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJson([
                'operations' => [],
                'clients' => [],
            ]);
    }
}
