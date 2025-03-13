<?php

namespace Tests\Feature\Mechanic;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Garage;
use App\Models\Mechanic;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ConfirmationRdvTest extends TestCase
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
            'garage_id' => 1,
        ]);

        // Create a garage
        $this->garage = Garage::create([
            'id' => 1,
            'name' => 'Test Garage',
            'ref' => 'garage1',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'user_id' => $this->user->id,
            'virtualGarage' => false,
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'confirmation' => 'automatique',
        ]);

        // Create a mechanic
        $this->mechanic = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'teleuser_phone' => '0612345678',
            'status' => true, // Ensure the mechanic is active
        ]);

        // Authenticate as the mechanic
        $this->actingAs($this->mechanic, 'mechanic');
    }

    /** @test */
    public function it_displays_appointments_for_the_garage()
    {
        // Create appointments for the garage
        $appointment1 = Appointment::create([
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'email' => 'john@example.com',
            'categorie_de_service' => 'Oil Change',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
        ]);

        $appointment2 = Appointment::create([
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'email' => 'john1@example.com',
            'categorie_de_service' => 'Oil Change',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
        ]);

        // Make a request to the confirmation index route
        $response = $this->get(route('mechanic.confirmation'));

        // Assert the response status and view
        $response->assertStatus(200)
            ->assertViewIs('mechanic.reservation.confirmationList')
            ->assertViewHas('appointments')
            ->assertViewHas('searchDate');

        // Get the view data
        $appointments = $response->viewData('appointments');

        // Assert that the appointments are displayed
        $this->assertCount(2, $appointments);
    }

    /** @test */
    public function it_filters_appointments_by_search_date()
    {
        // Create appointments for the garage
        $appointment1 = Appointment::create([
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'email' => 'john@example.com',
            'categorie_de_service' => 'Oil Change',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
        ]);

        $appointment2 = Appointment::create([
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'email' => 'john1@example.com',
            'categorie_de_service' => 'Oil Change',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
        ]);

        // Make a request to the confirmation index route with a search date
        $response = $this->get(route('mechanic.confirmation', ['search' => now()->toDateString()]));

        // Assert the response status and view
        $response->assertStatus(200)
            ->assertViewIs('mechanic.reservation.confirmationList')
            ->assertViewHas('appointments')
            ->assertViewHas('searchDate', now()->toDateString());

        // Get the view data
        $appointments = $response->viewData('appointments');

        // Assert that only the appointment for the search date is displayed
        $this->assertCount(1, $appointments);
        $this->assertEquals(now()->toDateString(), $appointments->first()->appointment_day);
    }

    /** @test */
    public function it_accepts_an_appointment()
    {
        Notification::fake();

        // Create an appointment for the garage
        $appointment = Appointment::create([
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'email' => 'john@example.com',
            'categorie_de_service' => 'Oil Change',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
        ]);


        // Make a request to accept the appointment
        $response = $this->put(route('mechanic.confirmation.accepter', $appointment->id));

        // Assert the response status and session flash messages
        $response->assertRedirect()
            ->assertSessionHas('success', 'Rendez-vous')
            ->assertSessionHas('subtitle', 'le status de rendez-vous a été modifié avec succès .');

        // Assert that the appointment status is updated
        $this->assertEquals('confirmé', $appointment->fresh()->status);

        // Assert that a notification was sent
        Notification::assertSentTo(
            [$appointment->user_email],
            \App\Notifications\GarageAcceptRdv::class
        );
    }

    /** @test */
    public function it_cancels_an_appointment()
    {
        Notification::fake();
        $appointment = Appointment::create([
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'email' => 'john@example.com',
            'categorie_de_service' => 'Oil Change',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
        ]);

        // Make a request to cancel the appointment
        $response = $this->put(route('mechanic.confirmation.annuler', $appointment->id));

        // Assert the response status and session flash messages
        $response->assertRedirect()
            ->assertSessionHas('success', 'Rendez-vous')
            ->assertSessionHas('subtitle', 'le status de rendez-vous a été modifié avec succès .');

        // Assert that the appointment status is updated
        $this->assertEquals('annulé', $appointment->fresh()->status);

        // Assert that a notification was sent
        Notification::assertSentTo(
            [$appointment->user_email],
            \App\Notifications\GarageCancelledRdv::class
        );
    }
}
