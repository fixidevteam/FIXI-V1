<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Garage;
use App\Models\Mechanic;
use App\Models\Appointment;
use App\Models\jour_indisponible;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class MechanicReservationControllerTest extends TestCase
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
    public function it_displays_calendar_with_appointments_and_unavailable_days()
    {
        // Create an appointment for the garage
        $appointment = Appointment::create([
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'user_email' => 'john@example.com',
            'garage_ref' => $this->garage->ref,
            'appointment_day' => now()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours',
        ]);

        // Create an unavailable day for the garage
        $unavailableDay = jour_indisponible::create([
            'garage_ref' => $this->garage->ref,
            'date' => now()->addDays(2)->format('Y-m-d'),
        ]);

        // Make the request
        $response = $this->get(route('mechanic.reservation.index'));

        // Assert the response
        $response->assertStatus(200)
            ->assertViewIs('mechanic.reservation.index')
            ->assertViewHas('appointments'); // Verify appointments are passed to the view

        // Verify the appointments data structure
        $appointments = $response->viewData('appointments');
        $this->assertCount(2, $appointments); // 1 appointment + 1 unavailable day

        // Verify the appointment data
        $this->assertEquals('Reservation: John Doe', $appointments[0]['title']);
        $this->assertEquals(now()->format('Y-m-d') . 'T10:00:00', $appointments[0]['start']); // Include seconds
        $this->assertEquals('orange', $appointments[0]['color']);

        // Verify the unavailable day data
        $this->assertEquals('Indisponible', $appointments[1]['title']);
        $this->assertEquals(now()->addDays(2)->format('Y-m-d'), $appointments[1]['start']);
        $this->assertEquals('gray', $appointments[1]['color']);
    }

    /** @test */
    public function it_displays_a_list_of_appointments()
    {
        // Create appointments for the garage
        $appointment1 = Appointment::create([
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'user_email' => 'john@example.com',
            'garage_ref' => $this->garage->ref,
            'appointment_day' => now()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours',
        ]);

        $appointment2 = Appointment::create([
            'user_full_name' => 'Jane Doe',
            'user_phone' => '0987654321',
            'user_email' => 'jane@example.com',
            'garage_ref' => $this->garage->ref,
            'appointment_day' => now()->addDay()->format('Y-m-d'),
            'appointment_time' => '11:00:00',
            'categorie_de_service' => 'Tire Rotation',
            'modele' => 'Camry',
            'objet_du_RDV' => 'Tire Maintenance',
            'status' => 'confirmé',
        ]);

        // Make the request
        $response = $this->get(route('mechanic.reservation.list'));

        // Assert the response
        $response->assertStatus(200)
            ->assertViewIs('mechanic.reservation.list')
            ->assertViewHas('appointments'); // Verify appointments are passed to the view

        // Verify the appointments data
        $appointments = $response->viewData('appointments');
        $this->assertCount(2, $appointments->items()); // Verify paginated appointments
    }

    /** @test */
    public function it_displays_a_specific_appointment()
    {
        // Create an appointment for the garage
        $appointment = Appointment::create([
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'user_email' => 'john@example.com',
            'garage_ref' => $this->garage->ref,
            'appointment_day' => now()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours',
        ]);

        // Make the request
        $response = $this->get(route('mechanic.reservation.show', $appointment->id));

        // Assert the response
        $response->assertStatus(200)
            ->assertViewIs('mechanic.reservation.show')
            ->assertViewHas('appointment'); // Verify the appointment is passed to the view

        // Verify the appointment data
        $appointmentData = $response->viewData('appointment');
        $this->assertEquals($appointment->id, $appointmentData->id);
    }

    /** @test */
    /** @test */
    public function it_updates_the_status_of_an_appointment()
    {
        // Create an appointment for the garage
        $appointment = Appointment::create([
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'user_email' => 'john@example.com',
            'garage_ref' => $this->garage->ref,
            'appointment_day' => now()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours',
        ]);

        // Mock the Notification facade
        Notification::fake();

        // New status data
        $newStatus = ['status' => 'confirmé'];

        // Set the previous URL to the reservation index page
        session(['previous_url' => route('mechanic.reservation.index')]);

        // Make the request to update the status
        $response = $this->patch(route('mechanic.reservation.updateStatus', $appointment->id), $newStatus);

        // Assert the response
        $response->assertStatus(302) // Redirects after update
            ->assertRedirect(route('mechanic.reservation.index')) // Verify the redirect URL
            ->assertSessionHas('success', 'Le statut a été mis à jour avec succès.');

        // Verify the appointment status was updated
        $updatedAppointment = Appointment::find($appointment->id);
        $this->assertEquals('confirmé', $updatedAppointment->status);

        // Verify the notification was sent
        // Notification::assertSentTo(
        //     [$appointment->user_email],
        //     \App\Notifications\GarageAcceptRdv::class
        // );
    }

    /** @test */
    public function it_handles_updating_status_to_cancelled()
    {
        // Create an appointment for the garage
        $appointment = Appointment::create([
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'user_email' => 'john@example.com',
            'garage_ref' => $this->garage->ref,
            'appointment_day' => now()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours',
        ]);

        // Mock the Notification facade
        Notification::fake();

        // New status data
        $newStatus = ['status' => 'annulé'];

        // Set the previous URL to the reservation index page
        session(['previous_url' => route('mechanic.reservation.index')]);

        // Make the request to update the status
        $response = $this->patch(route('mechanic.reservation.updateStatus', $appointment->id), $newStatus);

        // Assert the response
        $response->assertStatus(302) // Redirects after update
            ->assertRedirect(route('mechanic.reservation.index')) // Verify the redirect URL
            ->assertSessionHas('success', 'Le statut a été mis à jour avec succès.');

        // Verify the appointment status was updated
        $updatedAppointment = Appointment::find($appointment->id);
        $this->assertEquals('annulé', $updatedAppointment->status);

        // Verify the notification was sent
        // Notification::assertSentTo(
        //     [$appointment->user_email],
        //     \App\Notifications\GarageCancelledRdv::class
        // );
    }
}
