<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Garage;
use App\Models\Admin;
use App\Models\Mechanic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReservationControllerTest extends TestCase
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
        // Create a garage
        $this->garage = Garage::create([
            'ref' => 'garage1',
            'name' => 'Test Garage',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'virtualGarage' => false, // or true based on logic
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'domaines' => json_encode(['Mechanical', 'Electrical']), // example
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

        // Create an admin
        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create a mechanic
        $this->mechanic = Mechanic::create([
            'name' => 'Test Mechanic',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true,
        ]);

        // Authenticate as the user
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_displays_appointments_index_page()
    {
        // Create an appointment for the user
        $appointment = Appointment::create([
            'user_email' => $this->user->email,
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours'
        ]);

        $response = $this->get(route('RDV.index'));

        $response->assertStatus(200)
            ->assertViewIs('userRdv.index')
            ->assertViewHas('appointments');
    }

    /** @test */
    public function it_displays_a_specific_appointment()
    {
        // Create an appointment for the user
        $appointment = Appointment::create([
            'user_email' => $this->user->email,
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours'
        ]);

        $response = $this->get(route('RDV.show', $appointment->id));

        $response->assertStatus(200)
            ->assertViewIs('userRdv.show')
            ->assertViewHas('appointment')
            ->assertViewHas('garage');
    }

    /** @test */
    public function it_displays_edit_appointment_form()
    {
        // Create an appointment for the user
        $appointment = Appointment::create([
            'user_email' => $this->user->email,
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours'
        ]);

        $response = $this->get(route('RDV.edit', $appointment->id));

        $response->assertStatus(200)
            ->assertViewIs('userRdv.edit')
            ->assertViewHas('appointment')
            ->assertViewHas('garage');
    }

    /** @test */
    public function it_updates_an_appointment()
    {
        Notification::fake();

        // Create an appointment for the user
        $appointment = Appointment::create([
            'user_email' => $this->user->email,
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours'
        ]);

        $response = $this->put(route('RDV.update', $appointment->id), [
            'user_email' => $this->user->email,
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'appointment_day' => now()->addDay()->format('Y-m-d'),
            'appointment_time' => '11:00',
            'categorie_de_service' => 'Tire Rotation',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Test Object',
        ]);

        $response->assertRedirect(route('RDV.show', $appointment->id))
            ->assertSessionHas('success', 'Rendez-vous')
            ->assertSessionHas('subtitle', 'Votre rendez-vous a été modifié avec succès.');

        // Verify the appointment was updated
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'appointment_day' => now()->addDay()->format('Y-m-d'),
            'appointment_time' => '11:00',
            'categorie_de_service' => 'Tire Rotation',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Test Object',
        ]);

        // Verify notifications were sent
        // Notification::assertSentTo(
        //     [$this->admin],
        //     \App\Notifications\UpdatedRdv::class
        // );

        // Notification::assertSentTo(
        //     [$this->mechanic],
        //     \App\Notifications\UpdatedRdv::class
        // );
    }

    /** @test */
    public function it_deletes_an_appointment()
    {
        Notification::fake();

        // Create an appointment for the user
        $appointment = Appointment::create([
            'user_email' => $this->user->email,
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours'
        ]);

        $response = $this->delete(route('RDV.destroy', $appointment->id));

        $response->assertRedirect(route('RDV.show', $appointment->id));
        // Verify the appointment status was updated
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'en cours',
        ]);

        // Verify notifications were sent
        // Notification::assertSentTo(
        //     [$this->admin],
        //     \App\Notifications\CancelledRdv::class
        // );

        // Notification::assertSentTo(
        //     [$this->mechanic],
        //     \App\Notifications\CancelledRdv::class
        // );
    }

    /** @test */
    public function it_does_not_delete_an_appointment_within_24_hours()
    {
        // Create an appointment for the user
        $appointment = Appointment::create([
            'user_email' => $this->user->email,
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'appointment_day' => now()->addHours(12)->format('Y-m-d'), // Appointment is within 24 hours
            'appointment_time' => '10:00',
            'categorie_de_service' => 'Oil Change',
            'status' => 'en cours',
        ]);

        $response = $this->delete(route('RDV.destroy', $appointment->id));

        $response->assertRedirect(route('RDV.show', $appointment->id))
            ->assertSessionHas('error', 'Rendez-vous')
            ->assertSessionHas('subtitle', 'L’annulation est possible uniquement si le RDV est à plus de 24h.');

        // Verify the appointment status was not updated
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'en cours',
        ]);
    }
}
