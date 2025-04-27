<?php

namespace Tests\Feature;

use App\Models\garage;
use Tests\TestCase;
use App\Models\Mechanic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class MechanicProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Mechanic $mechanic;

    protected function setUp(): void
    {
        parent::setUp();

        $this->garage = garage::create([

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

        $this->mechanic = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true,
        ]);

        $this->actingAs($this->mechanic, 'mechanic');
    }

    /** @test */
    public function it_displays_the_profile_edit_form()
    {
        $response = $this->get(route('mechanic.profile.edit'));

        $response->assertStatus(200)
            ->assertViewIs('mechanic.profile.edit')
            ->assertViewHas('user');
    }

    /** @test */
    public function it_updates_the_profile_information()
    {
        $response = $this->put(route('mechanic.profile.update'), [
            'name' => 'Updated Mechanic',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect(route('mechanic.profile.edit'))
            ->assertSessionHas('status', 'profile-updated');

        $this->assertDatabaseHas('mechanics', [
            'id' => $this->mechanic->id,
            'name' => 'Updated Mechanic',
            'email' => 'updated@example.com',
        ]);
    }

    /** @test */
    public function it_resets_email_verification_when_email_changes()
    {
        $this->mechanic->email_verified_at = now();
        $this->mechanic->save();

        $response = $this->put(route('mechanic.profile.update'), [
            'name' => 'Mechanic Updated',
            'email' => 'newemail@example.com',
        ]);

        $this->mechanic->refresh();

        $this->assertNull($this->mechanic->email_verified_at);
    }

    /** @test */
    public function it_deletes_the_mechanic_account_with_correct_password()
    {
        $response = $this->delete(route('mechanic.profile.destroy'), [
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertGuest('mechanic');
        $this->assertDatabaseMissing('mechanics', ['id' => $this->mechanic->id]);
    }

    /** @test */
    public function it_fails_to_delete_account_with_wrong_password()
    {
        $response = $this->from(route('mechanic.profile.edit'))->delete(route('mechanic.profile.destroy'), [
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('mechanic.profile.edit'));
        $response->assertSessionHasErrors('userDeletion');
        $this->assertDatabaseHas('mechanics', ['id' => $this->mechanic->id]);
    }
}
