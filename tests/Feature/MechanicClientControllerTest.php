<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Voiture;
use App\Models\MarqueVoiture;
use App\Models\Ville;
use App\Models\Operation;
use App\Models\Garage;
use App\Models\Mechanic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MechanicClientControllerTest extends TestCase
{
    use RefreshDatabase;

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
            'date_operation' => now()->format('Y-m-d'), // Add the 'date_operation' field
            'status' => 'completed',
        ]);

        // Create marques and villes for the create form
        $this->marque = MarqueVoiture::create(['marque' => 'Toyota']);
        $this->ville = Ville::create(['ville' => 'Test City']);

        // Authenticate as the mechanic
        $this->actingAs($this->mechanic, 'mechanic');
    }

    /** @test */
    public function it_displays_a_list_of_clients()
    {
        $response = $this->get('/fixi-pro/clients');

        $response->assertStatus(200)
            ->assertViewIs('mechanic.clients.index')
            ->assertViewHas('clients');
    }

    /** @test */
    public function it_displays_the_create_client_form()
    {
        $response = $this->get('/fixi-pro/clients/create');

        $response->assertStatus(200)
            ->assertViewIs('mechanic.clients.create')
            ->assertViewHas('marques')
            ->assertViewHas('villes');
    }

    /** @test */
    public function it_stores_a_new_client()
    {
        $data = [
            'name' => 'New Client',
            'ville' => 'Test City',
            'email' => 'newclient@example.com',
            'telephone' => '0612345678',
            'part1' => '12345',
            'part2' => 'A',
            'part3' => '67',
            'marque' => 'Toyota',
            'modele' => 'Corolla',
        ];

        $response = $this->post('/fixi-pro/clients', $data);

        $response->assertRedirect(route('mechanic.clients.index'))
            ->assertSessionHas('success', 'client ajoutée')
            ->assertSessionHas('subtitle', 'le client  a été ajoutée avec succès à la liste.');

        // Verify the client was created
        $this->assertDatabaseHas('users', [
            'name' => 'New Client',
            'email' => 'newclient@example.com',
            'telephone' => '0612345678',
            'created_by_mechanic' => 1,
            'mechanic_id' => $this->mechanic->id,
        ]);

        // Verify the voiture was created
        $this->assertDatabaseHas('voitures', [
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
        ]);
    }

    /** @test */
    public function it_displays_a_client()
    {
        $response = $this->get('/fixi-pro/clients/' . $this->client->id);

        $response->assertStatus(200)
            ->assertViewIs('mechanic.clients.show')
            ->assertViewHas('client');
    }
}
