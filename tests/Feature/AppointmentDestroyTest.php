<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Appointment;
use App\Models\garage;
use App\Models\GarageSchedule;
use App\Models\GarageUnavailableTime;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentDestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an appointment more than 24 hours away is cancelled.
     */
    public function test_destroy_appointment_successfully()
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
        // Selected date
        $selectedDate = now()->addDays(3)->toDateString();
        $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek;

        // Create garage schedule (9 AM to 5 PM)
        GarageSchedule::create([
            'garage_ref' => $garage->ref,
            'available_day' => $dayOfWeek,
            'available_from' => '09:00:00',
            'available_to' => '17:00:00',
        ]);

        // Mark 12 PM to 1 PM as unavailable
        GarageUnavailableTime::create([
            'garage_ref' => $garage->ref,
            'unavailable_day' => $dayOfWeek,
            'unavailable_from' => '12:00:00',
            'unavailable_to' => '13:00:00',
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
        $response = $this->actingAs($user)
            ->delete(route('RDV.destroy', $appointment->id));

        // Refresh the appointment instance.
        $appointment->refresh();

        // Assert that the appointment's status was updated to 'cancelled'.
        $this->assertEquals('cancelled', $appointment->status);

        // Assert that flash messages indicate a successful cancellation.
        $response->assertSessionHas('success', 'Rendez-vous');
        $response->assertSessionHas('subtitle', 'Votre rendez-vous a été annulé avec succès.');

        // Assert redirection to the appointment's show page.
        $response->assertRedirect(route('RDV.show', $appointment));
    }

    /**
     * Test that an appointment less than 24 hours away is not cancelled.
     */
    public function test_destroy_appointment_within_24_hours()
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
        // Selected date
        $selectedDate = now()->addDays(3)->toDateString();
        $dayOfWeek = Carbon::parse($selectedDate)->dayOfWeek;

        // Create garage schedule (9 AM to 5 PM)
        GarageSchedule::create([
            'garage_ref' => $garage->ref,
            'available_day' => $dayOfWeek,
            'available_from' => '09:00:00',
            'available_to' => '17:00:00',
        ]);

        // Mark 12 PM to 1 PM as unavailable
        GarageUnavailableTime::create([
            'garage_ref' => $garage->ref,
            'unavailable_day' => $dayOfWeek,
            'unavailable_from' => '12:00:00',
            'unavailable_to' => '13:00:00',
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
            'appointment_day'  => Carbon::now()->addHours(23)->toDateTimeString(),
            'appointment_time' => '10:00',
            'status' => 'en_cour',
        ]);


        $response = $this->actingAs($user)
            ->delete(route('RDV.destroy', $appointment->id));

        // Refresh the appointment instance.
        $appointment->refresh();

        // The appointment status should remain unchanged.
        $this->assertNotEquals('cancelled', $appointment->status);

        // Assert that the error flash messages are set.
        $response->assertSessionHas('error', 'Rendez-vous');
        $response->assertSessionHas('subtitle', "L’annulation est possible uniquement si le RDV est à plus de 24h.");

        // Assert redirection to the appointment's show page.
        $response->assertRedirect(route('RDV.show', $appointment));
    }

    /**
     * Test that attempting to destroy a non-existent appointment flashes an error.
     */
    public function test_destroy_nonexistent_appointment()
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
        $nonExistingId = 999;

        $response = $this->actingAs($user)
            ->delete(route('RDV.destroy', $nonExistingId));

        // Assert that an error flash message is set.
        $response->assertSessionHas('error', 'Rendez-vous introuvable.');
    }

    /**
     * Test that a user cannot cancel an appointment that does not belong to them.
     */
}
