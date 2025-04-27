<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Garage;
use App\Models\Appointment;
use App\Models\jour_indisponible;
use App\Models\Mechanic;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MechanicDashboardControllerTest extends TestCase
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

        // Create a user (mechanic) associated with the garage
        $this->user = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id, // Ensure the garage is associated
            'telephone' => '0612345678',
            'status' => true,
        ]);

        // Authenticate as the user
        $this->actingAs($this->user, 'mechanic');
    }

    /** @test */
    public function it_displays_dashboard_with_rdv_count_and_appointments()
    {
        // Create appointments for the garage
        $appointment1 = Appointment::create([
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'user_email' => 'ymofie@gmail.com',
            'garage_ref' => $this->garage->ref,
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'en cours',
        ]);

        $appointment2 = Appointment::create([
            'user_full_name' => 'Jane Doe',
            'user_phone' => '0987654321',
            'user_email' => 'jane@gmail.com',
            'garage_ref' => $this->garage->ref,
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '11:00:00',
            'categorie_de_service' => 'Tire Rotation',
            'modele' => 'Camry',
            'objet_du_RDV' => 'Tire Maintenance',
            'status' => 'confirmé',
        ]);

        // Create an unavailable day
        $unavailableDay = jour_indisponible::create([
            'garage_ref' => $this->garage->ref,
            'date' => Carbon::today()->addDays(2)->format('Y-m-d'),
        ]);

        // Make the request
        $response = $this->get(route('mechanic.dashboard'));

        // Assert the response
        $response->assertStatus(200)
            ->assertViewIs('mechanic.dashboard')
            ->assertViewHas('Rdvcount', 2) // Verify the RDV count
            ->assertViewHas('appointments'); // Verify the appointments data
    }

    // /** @test */
    // public function it_handles_no_garage_associated()
    // {
    //     // Create a user without a garage
    //     $userWithoutGarage = Mechanic::create([
    //         'name' => 'No Garage User',
    //         'email' => 'no-garage@example.com',
    //         'password' => bcrypt('password'),
    //         'garage_id' => null, // No garage associated
    //         'telephone' => '0612345678',
    //         'status' => true,
    //     ]);

    //     // Authenticate as the user without a garage
    //     $this->actingAs($userWithoutGarage);

    //     // Make the request
    //     $response = $this->get(route('mechanic.dashboard'));

    //     // Assert the response
    //     $response->assertStatus(302) // Redirects back
    //         ->assertRedirect() // Ensure it redirects
    //         ->assertSessionHas('error', 'Garage not found.'); // Verify the error message
    // }

    /** @test */
    public function it_handles_no_appointments_or_unavailable_days()
    {
        // Make the request without creating any appointments or unavailable days
        $response = $this->get(route('mechanic.dashboard'));

        // Assert the response
        $response->assertStatus(200)
            ->assertViewIs('mechanic.dashboard')
            ->assertViewHas('Rdvcount', 0) // Verify the RDV count is 0
            ->assertViewHas('appointments', []); // Verify the appointments data is empty
    }
}
