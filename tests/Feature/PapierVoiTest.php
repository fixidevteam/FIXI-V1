<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Voiture;
use App\Models\VoiturePapier;
use App\Models\type_papierv;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PapierVoiTest extends TestCase
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

        // Create a voiture for the user
        $this->voiture = Voiture::create([
            'user_id' => $this->user->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
            'photo' => 'photo.jpg',
        ]);

        // Create a type_papierv
        $this->type = type_papierv::create([
            'type' => 'Test Type',
        ]);

        // Authenticate as the user
        $this->actingAs($this->user);

        // Set the voiture_id in the session
        Session::put('voiture_id', $this->voiture->id);
    }

    /** @test */
    public function it_displays_index_papier_form()
    {
        $response = $this->get(route('paiperVoiture.index'));

        $response->assertStatus(403);
    }
    /** @test */
    public function it_displays_create_papier_form()
    {
        $response = $this->get(route('paiperVoiture.create'));

        $response->assertStatus(200)
            ->assertViewIs('userPaiperVoiture.create')
            ->assertViewHas('types');
    }

    /** @test */
    public function it_stores_a_new_papier()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post(route('paiperVoiture.store'), [
            'type' => 'Test Type',
            // 'photo' => $file,
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('voiture.show', $this->voiture->id))
            ->assertSessionHas('success', 'Document ajouté')
            ->assertSessionHas('subtitle', 'Votre document a été ajouté avec succès à la liste.');

        // Verify the papier was created
        $this->assertDatabaseHas('voiture_papiers', [
            'voiture_id' => $this->voiture->id,
            'type' => 'Test Type',
        ]);
    }

    /** @test */
    public function it_displays_a_specific_papier()
    {
        // Create a voiture papier
        $papier = VoiturePapier::create([
            'voiture_id' => $this->voiture->id,
            'type' => 'Test Type',
            'photo' => 'user/paiperVoiture/test.jpg',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->get(route('paiperVoiture.show', $papier->id));

        $response->assertStatus(200)
            ->assertViewIs('userPaiperVoiture.show')
            ->assertViewHas('papier')
            ->assertViewHas('daysRemaining')
            ->assertViewHas('isCloseToExpiry')
            ->assertViewHas('fileExtension');
    }

    /** @test */
    public function it_displays_edit_papier_form()
    {
        // Create a voiture papier
        $papier = VoiturePapier::create([
            'voiture_id' => $this->voiture->id,
            'type' => 'Test Type',
            'photo' => 'user/paiperVoiture/test.jpg',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->get(route('paiperVoiture.edit', $papier->id));

        $response->assertStatus(200)
            ->assertViewIs('userPaiperVoiture.edit')
            ->assertViewHas('papier')
            ->assertViewHas('types');
    }

    /** @test */
    public function it_updates_a_papier()
    {
        Storage::fake('public');

        // Create a voiture papier
        $papier = VoiturePapier::create([
            'voiture_id' => $this->voiture->id,
            'type' => 'Test Type',
            'photo' => 'user/paiperVoiture/test.jpg',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $file = UploadedFile::fake()->image('updated_test.jpg');

        $response = $this->put(route('paiperVoiture.update', $papier->id), [
            'type' => 'Updated Test Type',
            // 'photo' => $file,
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('voiture.show', $this->voiture->id))
            ->assertSessionHas('success', 'Document mis à jour')
            ->assertSessionHas('subtitle', 'Votre document a été mis à jour avec succès.');

        // Verify the papier was updated
        $this->assertDatabaseHas('voiture_papiers', [
            'id' => $papier->id,
            'type' => 'Updated Test Type',
        ]);
    }

    /** @test */
    public function it_deletes_a_papier()
    {
        // Create a voiture papier
        $papier = VoiturePapier::create([
            'voiture_id' => $this->voiture->id,
            'type' => 'Test Type',
            'photo' => 'user/paiperVoiture/test.jpg',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->delete(route('paiperVoiture.destroy', $papier->id));

        $response->assertRedirect(route('voiture.show', $this->voiture->id))
            ->assertSessionHas('success', 'Document supprimée')
            ->assertSessionHas('subtitle', 'Votre document a été supprimée avec succès.');

        // Verify the papier was deleted
        // Verify the papier was soft deleted
        $this->assertNotNull($papier->fresh()->deleted_at);

    }
}
