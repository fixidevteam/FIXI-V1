<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Garage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetRdvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_authenticated_user_can_view_their_appointments()
    {
        // Create user
        $user = User::factory()->create(['status' => 1, 'ville' => 'marrakech']);
        // Create garage
        $garage = garage::create([
            'id' => 1,
            'name' => 'Auto Atlas',
            'ref' => 'GAR-00001',
            'localisation' => 'marrakech, cherifia',
            'ville' => 'Marrakech',
        ]);

        // Create appointments for the user
        $appointment = Appointment::create([
            'user_full_name' => $user->name,
            'user_phone' => '1234567890',
            'user_email' => $user->email,
            'garage_ref' => $garage->ref,
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Toyota Corolla',
            'numero_immatriculation' => 'ABC-1234',
            'objet_du_RDV' => 'Routine check-up',
            'appointment_day' => now()->addDays(3)->toDateString(),
            'appointment_time' => '10:00',
            'status' => 'en_cour',
        ]);

        // Act as the authenticated user
        $response = $this->actingAs($user)->get('fixi-plus/RDV');

        // Assert the response is OK
        $response->assertStatus(200);

        // Assert the view contains the appointment data
        $response->assertViewHas('appointments', function ($appointments) use ($appointment) {
            return $appointments->contains($appointment);
        });
    }

    /** @test */
    public function it_shows_error_if_no_appointments_exist()
    {
        // Create user without appointments
        $user = User::factory()->create(['status' => 1, 'ville' => 'marrakech']);

        // Act as the authenticated user
        $response = $this->actingAs($user)->get('fixi-plus/RDV');

        // Assert the user is redirected back with an error message
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Vous n\'avez aucun rendez-vous.');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_appointments()
    {
        $response = $this->get('fixi-plus/RDV');

        // Assert the user is redirected to the login page
        $response->assertRedirect('fixi-plus/login');
    }
}
