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

    protected $user;
    protected $garage;
    protected $mechanic;
    protected $voiture;
    protected $categorie;
    protected $operation;
    protected $sousOperation;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'Marrakech',
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

        // Create a voiture first since operations depend on it
        $this->voiture = Voiture::create([
            'user_id' => $this->user->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
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

        // Create a mechanic
        $this->mechanic = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true,
        ]);

        // Authenticate as the mechanic
        $this->actingAs($this->mechanic, 'mechanic');
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
            'garage_id' => $this->garage->id,
            'voiture_id' => $this->voiture->id, // Use the created voiture's id
            'kilometrage' => 1000, // Add required kilometrage
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('operations', [
            'categorie' => $this->categorie->id,
            'nom' => 'Autre',
            'autre_operation' => 'Custom Operation',
            'description' => 'Test description',
            'voiture_id' => $this->voiture->id,
            'garage_id' => $this->garage->id,
        ]);
    }

    /** @test */
    public function it_shows_a_specific_operation()
    {
        $operation = Operation::create([
            'garage_id' => $this->garage->id,
            'categorie' => $this->categorie->id,
            'nom' => 'Test Operation',
            'description' => 'Test description',
            'voiture_id' => $this->voiture->id,
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
        $anotherGarage = Garage::create([
            'id' => 4,
            'ref' => 'garage188',
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

        $anotherVoiture = Voiture::create([
            'user_id' => $this->user->id,
            'marque' => 'Honda',
            'modele' => 'Civic',
            'numero_immatriculation' => '54321-B-89',
        ]);

        $operation = Operation::create([
            'garage_id' => $anotherGarage->id,
            'categorie' => $this->categorie->id,
            'nom' => 'Test Operation',
            'description' => 'Test description',
            'voiture_id' => $anotherVoiture->id,
            'date_operation' => now()->format('Y-m-d'),
            'kilometrage' => 1000,
        ]);

        $response = $this->get(route('mechanic.operations.show', $operation->id));

        $response->assertStatus(302);
    }
}
