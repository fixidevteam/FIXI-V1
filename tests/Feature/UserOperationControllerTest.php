<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voiture;
use App\Models\Garage;
use App\Models\Operation;
use App\Models\nom_categorie;
use App\Models\nom_operation;
use App\Models\nom_sous_operation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class UserOperationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_display_operations_index(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'fes',
            'status' => true
        ]);

        Auth::login($user);

        $voiture = Voiture::create([
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '123-A-45',
            'user_id' => $user->id,
        ]);

        Operation::create([
            'categorie' => 'Maintenance',
            'nom' => 'Oil Change',
            'description' => 'Routine oil change',
            'date_operation' => now(),
            'voiture_id' => $voiture->id,
        ]);

        $response = $this->actingAs($user)->get('fixi-plus/operation/');

        $response->assertStatus(200)
            ->assertViewIs('userOperations.index')
            ->assertViewHas('operations');
    }

    public function test_create_operation_form(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'fes',
            'status' => true
        ]);

        Auth::login($user);

        Garage::create([
            'name' => 'Test Garage',
            'ville' => $user->ville,
            'ref' => 'GAR-001',
        ]);

        $response = $this->actingAs($user)->get('fixi-plus/operation/create');

        $response->assertStatus(200)
            ->assertViewIs('userOperations.create')
            ->assertViewHas('garages');
    }

    public function test_store_operation(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'fes',
            'status' => true
        ]);

        Auth::login($user);

        $voiture = Voiture::create([
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '123-A-45',
            'user_id' => $user->id,
        ]);

        Session::put('voiture_id', $voiture->id);

        $file = UploadedFile::fake()->image('operation.jpg');

        $response = $this->actingAs($user)->post('fixi-plus/operation/', [
            'categorie' => 'Maintenance',
            'nom' => 'autre',
            'autre_operation' => 'test',
            'description' => 'Routine oil change',
            'date_operation' => now()->toDateString(),
            'photo' => $file,
            'voiture_id' => $voiture->id,
            'new_garage_name' => 'garage'
        ]);


        // $response->assertSessionHas('success', 'Operation ajoutée');
        // $response->assertSessionHas('subtitle', 'Votre Operation a été ajoutée avec succès à la liste.');
        $response->assertRedirect(route('voiture.show', $voiture));
        $this->assertDatabaseHas('operations', [
            'nom' => 'autre',
            'categorie' => 'Maintenance',
        ]);
    }

    public function test_show_operation(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'fes',
            'status' => true
        ]);

        Auth::login($user);

        $voiture = Voiture::create([
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '123-A-45',
            'user_id' => $user->id,
        ]);
        $file = UploadedFile::fake()->image('operation.jpg');
        $operation = Operation::create([
            'categorie' => 'Maintenance',
            'nom' => 'autre',
            'autre_operation' => 'test',
            'description' => 'Routine oil change',
            'date_operation' => now()->toDateString(),
            'photo' => $file,
            'voiture_id' => $voiture->id,
            'new_garage_name' => 'garage'
        ]);

        $response = $this->actingAs($user)->get('fixi-plus/operation/show', $operation->id);

        $response->assertStatus(200)
            ->assertViewIs('userOperations/show')
            ->assertViewHas('operation', $operation);
    }

    public function test_edit_operation_form(): void
    {
        nom_categorie::create([
            'nom_categorie' => 'test'
        ]);
        Garage::create([
            'name' => 'Test Garage',
            'ref' => 'garage1',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'localisation' => 'Test Location',
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'confirmation' => 'automatique',
        ]);
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'fes',
            'status' => true
        ]);

        Auth::login($user);

        $voiture = Voiture::create([
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '123-A-45',
            'user_id' => $user->id,
        ]);
        $file = UploadedFile::fake()->image('operation.jpg');
        $operation = Operation::create([
            'categorie' => 'Maintenance',
            'nom' => 'autre',
            'autre_operation' => 'test',
            'description' => 'Routine oil change',
            'date_operation' => now()->toDateString(),
            'photo' => $file,
            'voiture_id' => $voiture->id,
            'new_garage_name' => 'garage'
        ]);

        $response = $this->actingAs($user)->get('fixi-plus/operation/edit', $operation->id);

        $response->assertStatus(200);
        $response->assertViewIs('userOperations/edit');
        $response->assertViewHas('operation', $operation);
    }

    public function test_update_operation(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'fes',
            'status' => true
        ]);

        Auth::login($user);

        $voiture = Voiture::create([
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '123-A-45',
            'user_id' => $user->id,
        ]);
        $file = UploadedFile::fake()->image('operation.jpg');
        $operation = Operation::create([
            'categorie' => 'Maintenance',
            'nom' => 'autre',
            'autre_operation' => 'test',
            'description' => 'Routine oil change',
            'date_operation' => now()->toDateString(),
            'photo' => $file,
            'voiture_id' => $voiture->id,
            'new_garage_name' => 'garage'
        ]);

        $response = $this->actingAs($user)->put(route('operation.update', $operation->id), [
            'categorie' => 'Maintenance',
            'nom' => 'Updated Oil Change',
            'description' => 'Updated routine oil change',
            'date_operation' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('voiture.show', $voiture))
            ->assertSessionHas('success', 'Operation modifiée');

        $this->assertDatabaseHas('operations', [
            'nom' => 'Updated Oil Change',
            'description' => 'Updated routine oil change',
        ]);
    }
}
