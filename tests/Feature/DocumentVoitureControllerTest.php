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
use Illuminate\Support\Facades\Storage;

class DocumentVoitureControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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

        // Create the directory for storing files
        Storage::fake('public');
        Storage::disk('public')->makeDirectory('user/papierVoiture');
    }

    /** @test */
    public function it_displays_documents_index_page()
    {
        // Create a document for the voiture
        $document = VoiturePapier::create([
            'voiture_id' => $this->voiture->id,
            'type' => 'Test Type',
            'photo' => 'user/papierVoiture/test.jpg',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->get(route('documentVoiture.index'));

        $response->assertStatus(200)
            ->assertViewIs('userDocumentVoiture.index')
            ->assertViewHas('documents');
    }

    /** @test */
    public function it_displays_create_document_form()
    {
        $response = $this->get(route('documentVoiture.create'));

        $response->assertStatus(200)
            ->assertViewIs('userDocumentVoiture.create')
            ->assertViewHas('userVehicles')
            ->assertViewHas('types');
    }

    /** @test */
    public function it_stores_a_new_document()
    {
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post(route('documentVoiture.store'), [
            'type' => 'Test Type',
            // 'photo' => $file,
            'voiture_id' => $this->voiture->id,
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('documentVoiture.index'))
            ->assertSessionHas('success', 'Document ajouté')
            ->assertSessionHas('subtitle', 'Votre document a été ajouté avec succès à la liste.');

        // Verify the document was created
        $this->assertDatabaseHas('voiture_papiers', [
            'voiture_id' => $this->voiture->id,
            'type' => 'Test Type',
        ]);
    }
}
