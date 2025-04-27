<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Voiture;
use App\Models\MarqueVoiture;
use App\Models\Operation;
use App\Models\Garage;
use App\Models\Mechanic;
use Dompdf\Image\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache as FacadesCache;
use Illuminate\Support\Facades\Session;

class AMechanicsVoituresControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $garage;
    protected $mechanic;
    protected $client;
    protected $voiture;
    protected $operation;
    protected $marque;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'tehsjhjsst@example.com',
            'password' => bcrypt('password'),
            'ville' => 'Test City',
            'telephone' => '0612345678',
            'status' => 1,
        ]);

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

        // Create a mechanic
        $this->mechanic = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true,
        ]);

        // Create a client user
        $this->client = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'ville' => 'Test City',
            'telephone' => '0712345678',
            'status' => 1,
            'created_by_mechanic' => 1,
            'mechanic_id' => $this->garage->id,
        ]);

        // Create a voiture
        $this->voiture = Voiture::create([
            'user_id' => $this->client->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
        ]);

        // Create an operation
        $this->operation = Operation::create([
            'garage_id' => $this->garage->id,
            'voiture_id' => $this->voiture->id,
            'categorie' => 'Maintenance',
            'nom' => 'Oil Change',
            'description' => 'Regular oil change',
            'date_operation' => now()->format('Y-m-d'),
            'kilometrage' => 10000,
            'create_by' => 'mechanic',
        ]);

        // Create marques
        $this->marque = MarqueVoiture::create([
            'marque' => 'Toyota',
        ]);

        // Authenticate as the mechanic
        $this->actingAs($this->mechanic, 'mechanic');
    }

    /** @test */
    public function test_mechanic_voitures()
    {
        $response = $this->get(route('mechanic.voitures.index'));

        $response->assertStatus(200)
            ->assertViewIs('mechanic.voitures.index')
            ->assertViewHas('voitures');
    }

    /** @test */
    public function test_mechanic_create_voiture_form()
    {
        // Start session
        $this->withSession(['client' => $this->client]);

        $response = $this->get(route('mechanic.voitures.create'));

        $response->assertStatus(200)
            ->assertViewIs('mechanic.voitures.create')
            ->assertViewHas('marques')
            ->assertViewHas('client');
    }

    /** @test */
    public function test_mechanic_stores_voiture()
    {
        // Start session with client
        $this->withSession(['client' => $this->client]);

        $data = [
            'part1' => '54321',
            'part2' => 'B',
            'part3' => '89',
            'marque' => 'Honda',
            'modele' => 'Civic',
            'photo' => null,
        ];

        $response = $this->post(route('mechanic.voitures.store'), $data);

        $response->assertRedirect(route('mechanic.clients.show', $this->client))
            ->assertSessionHas('success')
            ->assertSessionHas('subtitle');

        // Verify the voiture was created
        $this->assertDatabaseHas('voitures', [
            'user_id' => $this->client->id,
            'marque' => 'Honda',
            'modele' => 'Civic',
            'numero_immatriculation' => '54321-B-89',
        ]);
    }

    /** @test */
    public function test_mechanic_voiture_show()
    {
        // Set voiture in session
        $this->withSession(['voiture_id' => $this->voiture->id]);

        $response = $this->get(route('mechanic.voitures.show', $this->voiture->id));

        $response->assertStatus(200)
            ->assertViewIs('mechanic.voitures.show')
            ->assertViewHas('voiture', $this->voiture)
            ->assertViewHas('operations')
            ->assertViewHas('nom_categories')
            ->assertViewHas('nom_operations')
            ->assertViewHas('client');
    }
}
