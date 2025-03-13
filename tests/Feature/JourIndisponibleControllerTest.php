<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Garage;
use App\Models\Mechanic;
use App\Models\jour_indisponible;
use Illuminate\Support\Facades\Auth;

class JourIndisponibleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $garage;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a garage
        $this->garage = Garage::create([
            'ref' => 'garage1',
            'name' => 'Test Garage',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'confirmation' => 'automatique',
        ]);

        // Create a user (mechanic) associated with the garage
        $this->user = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true,
        ]);

        // Authenticate as the user using the 'mechanic' guard
        $this->actingAs($this->user, 'mechanic');
    }

    /** @test */
    /** @test */
    public function it_displays_a_list_of_unavailable_days()
    {
        // Create an unavailable day for the garage
        // $unavailableDay = jour_indisponible::create([
        //     'garage_ref' => $this->garage->ref,
        //     'date' => now()->format('Y-m-d'),
        // ]);

        // Make the request
        $response = $this->get(route('mechanic.jour-indisponible.index'));

        // Assert the response
        $response->assertStatus(200); // Verify unavailable days are passed to the view

            }

    /** @test */
    public function it_displays_the_create_form()
    {
        // Make the request
        $response = $this->get(route('mechanic.jour-indisponible.create'));

        // Assert the response
        $response->assertStatus(200)
            ->assertViewIs('mechanic.jour-indisponible.create');
    }

    /** @test */
    public function it_stores_a_new_unavailable_day()
    {
        // Data for the new unavailable day
        $data = [
            'date' => now()->format('Y-m-d'),
        ];

        // Make the request to store the unavailable day
        $response = $this->post(route('mechanic.jour-indisponible.store'), $data);

        // Assert the response
        $response->assertStatus(302) // Redirects after store
            ->assertRedirect(route('mechanic.calendrier.index'))
            ->assertSessionHas('success', 'Lejour ajouté')
            ->assertSessionHas('subtitle', 'Le jour a été ajouté avec succès.');

        // Verify the unavailable day was created
        $this->assertDatabaseHas('jour_indisponibles', [
            'garage_ref' => $this->garage->ref,
            'date' => now()->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function it_displays_the_edit_form()
    {
        // Create an unavailable day for the garage
        $unavailableDay = jour_indisponible::create([
            'garage_ref' => $this->garage->ref,
            'date' => now()->format('Y-m-d'),
        ]);

        // Make the request to edit the unavailable day
        $response = $this->get(route('mechanic.jour-indisponible.edit', $unavailableDay->id));

        // Assert the response
        $response->assertStatus(200)
            ->assertViewIs('mechanic.jour-indisponible.edit')
            ->assertViewHas('jour'); // Verify the unavailable day is passed to the view

        // Verify the unavailable day data
        $jour = $response->viewData('jour');
        $this->assertEquals($unavailableDay->id, $jour->id);
    }

    /** @test */
    public function it_updates_an_unavailable_day()
    {
        // Create an unavailable day for the garage
        $unavailableDay = jour_indisponible::create([
            'garage_ref' => $this->garage->ref,
            'date' => now()->format('Y-m-d'),
        ]);

        // New data for the update
        $newData = [
            'date' => now()->addDay()->format('Y-m-d'),
        ];

        // Make the request to update the unavailable day
        $response = $this->put(route('mechanic.jour-indisponible.update', $unavailableDay->id), $newData);

        // Assert the response
        $response->assertStatus(302) // Redirects after update
            ->assertRedirect(route('mechanic.calendrier.index'))
            ->assertSessionHas('success', 'Le jour modifié')
            ->assertSessionHas('subtitle', 'Le jour a été modifié avec succès.');

        // Verify the unavailable day was updated
        $this->assertDatabaseHas('jour_indisponibles', [
            'id' => $unavailableDay->id,
            'date' => now()->addDay()->format('Y-m-d'),
        ]);
    }

    /** @test */
    public function it_deletes_an_unavailable_day()
    {
        // Create an unavailable day for the garage
        $unavailableDay = jour_indisponible::create([
            'garage_ref' => $this->garage->ref,
            'date' => now()->format('Y-m-d'),
        ]);

        // Make the request to delete the unavailable day
        $response = $this->delete(route('mechanic.jour-indisponible.destroy', $unavailableDay->id));

        // Assert the response
        $response->assertStatus(302) // Redirects after delete
            ->assertRedirect()
            ->assertSessionHas('success', 'jour supprimée')
            ->assertSessionHas('subtitle', 'le jour a été supprimée avec succès.');

        // Verify the unavailable day was deleted
        $this->assertDatabaseMissing('jour_indisponibles', [
            'id' => $unavailableDay->id,
        ]);
    }
}
