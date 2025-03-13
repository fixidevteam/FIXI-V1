<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\UserPapier;
use App\Models\type_papierp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PapierPersoTest extends TestCase
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

        // Create a type_papierp
        $this->type = type_papierp::create([
            'type' => 'Test Type',
        ]);

        // Authenticate as the user
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_displays_papiers_index_page()
    {
        // Create a user papier
        $papier = UserPapier::create([
            'user_id' => $this->user->id,
            'type' => 'Test Type',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->get(route('paiperPersonnel.index'));

        $response->assertStatus(200)
            ->assertViewIs('userPaiperPersonnel.index')
            ->assertViewHas('papiers');
    }

    /** @test */
    public function it_displays_create_papier_form()
    {
        $response = $this->get(route('paiperPersonnel.create'));

        $response->assertStatus(200)
            ->assertViewIs('userPaiperPersonnel.create')
            ->assertViewHas('types');
    }

    /** @test */
    public function it_stores_a_new_papier()
    {
        $response = $this->post(route('paiperPersonnel.store'), [
            'type' => 'Test Type',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('paiperPersonnel.index'))
            ->assertSessionHas('success', 'Document ajouté')
            ->assertSessionHas('subtitle', 'Votre document a été ajouté avec succès à la liste.');

        // Verify the papier was created
        $this->assertDatabaseHas('user_papiers', [
            'user_id' => $this->user->id,
            'type' => 'Test Type',
        ]);
    }

    /** @test */
    public function it_displays_a_specific_papier()
    {
        // Create a user papier
        $papier = UserPapier::create([
            'user_id' => $this->user->id,
            'type' => 'Test Type',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->get(route('paiperPersonnel.show', $papier->id));

        $response->assertStatus(200)
            ->assertViewIs('userPaiperPersonnel.show')
            ->assertViewHas('papier')
            ->assertViewHas('daysRemaining')
            ->assertViewHas('isCloseToExpiry')
            ->assertViewHas('fileExtension');
    }

    /** @test */
    public function it_displays_edit_papier_form()
    {
        // Create a user papier
        $papier = UserPapier::create([
            'user_id' => $this->user->id,
            'type' => 'Test Type',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->get(route('paiperPersonnel.edit', $papier->id));

        $response->assertStatus(200)
            ->assertViewIs('userPaiperPersonnel.edit')
            ->assertViewHas('papier')
            ->assertViewHas('types');
    }

    /** @test */
    public function it_updates_a_papier()
    {
        // Create a user papier
        $papier = UserPapier::create([
            'user_id' => $this->user->id,
            'type' => 'Test Type',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->put(route('paiperPersonnel.update', $papier->id), [
            'type' => 'Updated Test Type',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('paiperPersonnel.index'))
            ->assertSessionHas('success', 'Document mis à jour')
            ->assertSessionHas('subtitle', 'Votre document a été mis à jour avec succès.');

        // Verify the papier was updated
        $this->assertDatabaseHas('user_papiers', [
            'id' => $papier->id,
            'type' => 'Updated Test Type',
        ]);
    }

    /** @test */
    public function it_deletes_a_papier()
    {
        // Create a user papier
        $papier = UserPapier::create([
            'user_id' => $this->user->id,
            'type' => 'Test Type',
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => now()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->delete(route('paiperPersonnel.destroy', $papier->id));

        $response->assertRedirect(route('paiperPersonnel.index'))
            ->assertSessionHas('success', 'Document supprimée')
            ->assertSessionHas('subtitle', 'Votre document a été supprimée avec succès.');

        // Verify the papier was soft deleted
        $this->assertNotNull($papier->fresh()->deleted_at);
    }
}
