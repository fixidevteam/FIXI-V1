<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Operation;
use App\Models\Garage;
use App\Models\Mechanic;
use App\Models\nom_categorie;
use App\Models\nom_operation;
use App\Models\nom_sous_operation;
use App\Models\SousOperation;
use App\Models\Voiture;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MechanicOperatioControllerTest extends TestCase
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
            'garage_id' => 1,
        ]);

        // Create a garage
        $this->garage = Garage::create([
            'id' => 1,
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

        // Create a nom_categorie
        $this->categorie = nom_categorie::create([
            'nom_categorie' => 'Test Category',
        ]);

        // Create a nom_operation
        $this->operation = nom_operation::create([
            'nom_operation' => 'Test Operation',
            'nom_categorie_id' => $this->categorie->id,
        ]);

        // Create a nom_sous_operation
        $this->sousOperation = nom_sous_operation::create([
            'nom_sous_operation' => 'Test Sous Operation',
            'nom_operation_id' => $this->operation->id,
        ]);

        // Create a voiture
        $this->voiture = Voiture::create([
            'user_id' => $this->user->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
            'photo' => 'photo.jpg',
        ]);

        // Create a mechanic
        $this->mechanic = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true, // Ensure the mechanic is active
        ]);

        // Authenticate as the mechanic
        $this->actingAs($this->mechanic, 'mechanic'); // Use the 'mechanic' guard
    }

    /** @test */
    public function it_displays_operations_index_page()
    {
        $response = $this->get(route('mechanic.operations.index'));

        $response->assertStatus(200)
            ->assertViewIs('mechanic.operations.index')
            ->assertViewHas('operations')
            ->assertViewHas('categories')
            ->assertViewHas('ope');
    }

    /** @test */
    public function it_stores_a_new_operation()
    {

        $response = $this->post(route('mechanic.operations.store'), [
            'categorie' => $this->categorie->id,
            'nom' => 'autre',
            'autre_operation' => 'Custom Operation',
            'description' => 'Test description',
            'date_operation' => now()->format('Y-m-d'),
            'kilometrage' => 1000,
            'voiture_id' => $this->voiture->id, // Add required 'voiture_id'
        ]);

        $response->assertRedirect();

        // Verify the operation was created
        $this->assertDatabaseHas('operations', [
            'categorie' => $this->categorie->id,
            'nom' => 'Autre',
            'autre_operation' => 'Custom Operation',
            'description' => 'Test description',
            'voiture_id' => $this->voiture->id, // Verify 'voiture_id'
        ]);

    }

    /** @test */
    public function it_shows_a_specific_operation()
    {
        // Create an operation for the garage
        $operation = Operation::create([
            'garage_id' => $this->garage->id,
            'categorie' => $this->categorie->id,
            'nom' => 'Test Operation',
            'description' => 'Test description',
            'voiture_id' => $this->voiture->id, // Add valid 'voiture_id'
            'date_operation' => now()->format('Y-m-d'),
            'kilometrage' => 1000,
        ]);

        $response = $this->get(route('mechanic.operations.show', $operation->id));

        $response->assertStatus(200)
            ->assertViewIs('mechanic.operations.show')
            ->assertViewHas('operation', $operation)
            ->assertViewHas('categories')
            ->assertViewHas('ope')
            ->assertViewHas('sousOperation');
    }

    /** @test */
    public function it_does_not_show_operation_from_another_garage()
    {
        // Create another garage
        $anotherGarage = Garage::create([
            'id' => 2,
            'name' => 'Another Garage',
            'ref' => 'garage2',
            'photo' => 'photo2.jpg',
            'ville' => 'Another City',
            'quartier' => 'Another Neighborhood',
            'localisation' => 'Another Location',
            'user_id' => $this->user->id,
            'virtualGarage' => false,
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'confirmation' => 'automatique',
        ]);

        // Create an operation for the other garage
        $operation = Operation::create([
            'garage_id' => $anotherGarage->id,
            'categorie' => $this->categorie->id,
            'nom' => 'Test Operation',
            'description' => 'Test description',
            'voiture_id' => $this->voiture->id, // Add valid 'voiture_id'
            'date_operation' => now()->format('Y-m-d'),
            'kilometrage' => 1000,
        ]);

        $response = $this->get(route('mechanic.operations.show', $operation->id));

        $response->assertStatus(403); // Assuming you return a 403 Forbidden response
    }
}
