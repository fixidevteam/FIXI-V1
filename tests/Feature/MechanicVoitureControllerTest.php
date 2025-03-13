<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;
use App\Models\Voiture;
use App\Models\MarqueVoiture;
use App\Models\Operation;
use App\Models\Garage;
use App\Models\Mechanic;
use App\Models\nom_categorie;
use App\Models\nom_operation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MechanicVoitureControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'Test City',
            'telephone' => '0612345678',
            'status' => 1,
        ]);

        // Create a garage associated with the user
        $this->garage = Garage::create([
            'name' => 'Test Garage',
            'ref' => 'garage1',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'user_id' => $this->user->id, // Use the created user's ID
            'virtualGarage' => false,
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'confirmation' => 'automatique',
        ]);

        // Create a mechanic associated with the garage
        $this->mechanic = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true, // Ensure the mechanic is active
        ]);

        // Create a client user
        $this->client = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'ville' => 'Test City',
            'telephone' => '0712345678',
            'status' => 0,
            'created_by_mechanic' => 1,
            'mechanic_id' => $this->mechanic->id,
        ]);

        // Create a voiture for the client
        $this->voiture = Voiture::create([
            'user_id' => $this->client->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
            'photo' => 'photo.jpg',
        ]);

        // Create an operation for the voiture
        $this->operation = Operation::create([
            'voiture_id' => $this->voiture->id,
            'garage_id' => $this->garage->id,
            'description' => 'Oil Change',
            'categorie' => 'Maintenance',
            'date_operation' => now()->format('Y-m-d'),
            'status' => 'completed',
        ]);

        // Create marques for the create form
        $this->marque = MarqueVoiture::create(['marque' => 'Toyota']);

        // Create nom_categories and nom_operations for the show form
        // $this->nom_categorie = nom_categorie::create(['nom_categorie_id'=>0,'nom_categorie' => 'Test Categorie']);
        // $this->nom_operation = nom_operation::create(['nom_operation' => 'Test Operation']);

        // Authenticate as the mechanic
        $this->actingAs($this->mechanic, 'mechanic');
    }

    /** @test */
    public function it_displays_a_list_of_voitures()
    {
        $response = $this->get('/fixi-pro/voitures');

        $response->assertStatus(200)
            ->assertViewIs('mechanic.voitures.index')
            ->assertViewHas('voitures');
    }

    /** @test */
    public function it_displays_the_create_voiture_form()
    {
        // Set the client in the session
        Session::put('client', $this->client);

        $response = $this->get('/fixi-pro/voitures/create');

        $response->assertStatus(200)
            ->assertViewIs('mechanic.voitures.create')
            ->assertViewHas('marques')
            ->assertViewHas('client');
    }

    /** @test */
    public function it_stores_a_new_voiture()
    {
        // Set the client in the session
        Session::put('client', $this->client);

        $data = [
            'part1' => '12345',
            'part2' => 'A',
            'part3' => '67',
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'photo' => null,
        ];

        $response = $this->post('/fixi-pro/voitures', $data);

        $response->assertRedirect(route('mechanic.clients.show', $this->client))
            ->assertSessionHas('success', 'voitire ajoutée')
            ->assertSessionHas('subtitle', 'la voiture  a été ajoutée avec succès à la liste.');

        // Verify the voiture was created
        $this->assertDatabaseHas('voitures', [
            'user_id' => $this->client->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
        ]);
    }

    /** @test */
    public function it_displays_a_voiture()
    {
        // Set the voiture in the session
        Session::put('voiture_id', $this->voiture->id);

        $response = $this->get('/fixi-pro/voitures/' . $this->voiture->id);

        $response->assertStatus(200)
            ->assertViewIs('mechanic.voitures.show')
            ->assertViewHas('voiture')
            ->assertViewHas('operations')
            ->assertViewHas('nom_categories')
            ->assertViewHas('nom_operations');
    }
}
