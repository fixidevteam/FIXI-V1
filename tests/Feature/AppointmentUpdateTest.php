<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\garage;
use App\Models\GarageSchedule;
use App\Models\GarageUnavailableTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppointmentUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /**
     * Test a successful appointment update.
     */
    public function test_update_appointment_successfully()
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
        // Data to update the appointment.
        $data = [
            'appointment_day'       => '2025-03-01',
            'appointment_time'      => '14:00:00',
            'categorie_de_service'  => 'Maintenance',
            'modele'                => 'Toyota',
            'objet_du_RDV'          => 'Oil change',
        ];

        // Act as the authenticated user.
        $response = $this->actingAs($user)
            ->patch(route('RDV.update', $appointment->id), $data);

        // Refresh the appointment instance.
        $appointment->refresh();

        // Assert the appointment was updated with the new data and the status.
        $this->assertEquals('en_cour', $appointment->status);
        $this->assertEquals($data['appointment_day'], $appointment->appointment_day);
        $this->assertEquals($data['appointment_time'], $appointment->appointment_time);
        $this->assertEquals($data['categorie_de_service'], $appointment->categorie_de_service);
        $this->assertEquals($data['modele'], $appointment->modele);
        $this->assertEquals($data['objet_du_RDV'], $appointment->objet_du_RDV);

        // Check that flash messages have been set.
        $response->assertSessionHas('success', 'Rendez-vous');
        $response->assertSessionHas('subtitle', 'Votre rendez-vous a été modifié avec succès.');

        // Confirm the user is redirected to the show page.
        $response->assertRedirect(route('RDV.show', $appointment));
    }
    public function test_update_nonexistent_appointment()
    {
        $user = User::factory()->create(['status' => 1, 'ville' => 'marrakech']);

        // Using an ID that doesn't exist.
        $nonExistingId = 999;

        $data = [
            'appointment_day'      => '2025-03-01',
            'appointment_time'     => '14:00',
            'categorie_de_service' => 'Maintenance',
        ];

        $response = $this->actingAs($user)
            ->patch(route('RDV.update', $nonExistingId), $data);

        // Should flash an error message.
        $response->assertSessionHas('error', 'Rendez-vous introuvable.');
    }
}
