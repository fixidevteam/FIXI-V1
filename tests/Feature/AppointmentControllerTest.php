<?php

namespace Tests\Feature;

use App\Mail\AppointmentVerificationMail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Garage;
use App\Models\GarageSchedule;
use App\Models\GarageUnavailableTime;
use App\Models\jour_indisponible;
use App\Models\MarqueVoiture;
use App\Models\Appointment;
use App\Models\User;
use App\Notifications\GarageAcceptRdv;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a User record first
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'Test City',
            'provider' => null,
            'provider_id' => null,
            'provider_token' => null,
            'telephone' => '1234567890',
            'status' => 1,
        ]);

        // Create necessary data for testing
        $this->garage = Garage::create([
            'name' => 'Test Garage',
            'ref' => 'garage1',
            'photo' => 'photo.jpg',
            'ville' => 'Test City',
            'quartier' => 'Test Neighborhood',
            'localisation' => 'Test Location',
            'user_id' => $this->user->id,
            'virtualGarage' => false,
            'services' => json_encode(['Oil Change', 'Tire Rotation']),
            'confirmation' => 'automatique', // Ensure this is set to 'automatique'
        ]);

        $this->schedule = GarageSchedule::create([
            'garage_ref' => $this->garage->ref,
            'available_day' => Carbon::today()->dayOfWeek,
            'available_from' => '09:00:00',
            'available_to' => '17:00:00',
        ]);

        $this->unavailableTime = GarageUnavailableTime::create([
            'garage_ref' => $this->garage->ref,
            'unavailable_day' => Carbon::today()->dayOfWeek,
            'unavailable_from' => '12:00:00',
            'unavailable_to' => '13:00:00',
        ]);

        $this->disabledDate = jour_indisponible::create([
            'garage_ref' => $this->garage->ref,
            'date' => Carbon::today()->format('Y-m-d'),
        ]);

        $this->marque = MarqueVoiture::create([
            'marque' => 'Toyota',
        ]);
    }

    /** @test */
    public function it_returns_available_dates()
    {
        $response = $this->getJson('/api/available-dates?garage_ref=garage1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'available_dates',
                'unavailable_dates',
                'services',
                'marques',
            ]);
    }

    /** @test */
    public function it_returns_time_slots_for_a_given_date()
    {
        $selectedDate = Carbon::today()->format('Y-m-d');

        $response = $this->getJson("/api/time-slots?garage_ref=garage1&date={$selectedDate}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'time_slots',
            ]);
    }

    /** @test */
    public function it_books_an_appointment_and_sends_verification_code()
    {
        Mail::fake();
        Cache::flush();

        $data = [
            'full_name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'john@example.com',
            'garage_ref' => 'garage1',
            'categorie_de_service' => 'Oil Change',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
        ];

        $response = $this->postJson('/api/book-appointment', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Verification code sent to your email. Please enter the code to confirm your appointment.',
                'status' => 'verification_required',
            ]);

        Mail::assertSent(AppointmentVerificationMail::class);
    }

    /** @test */
    public function it_verifies_appointment_and_creates_booking()
    {
        Notification::fake();
        Cache::flush();

        $email = 'john@example.com';
        $verificationCode = mt_rand(100000, 999999);
        Cache::put('verification_code_' . $email, $verificationCode, now()->addMinutes(10));

        $data = [
            'email' => $email,
            'verification_code' => $verificationCode,
            'full_name' => 'John Doe',
            'phone' => '1234567890',
            'garage_ref' => 'garage1',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
        ];

        $response = $this->postJson('/api/appointments/verify', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Appointment booked successfully!',
            ]);

        // Verify the appointment was created with status 'confirmé'
        $this->assertDatabaseHas('appointments', [
            'user_full_name' => 'John Doe',
            'user_phone' => '1234567890',
            'user_email' => $email,
            'garage_ref' => 'garage1',
            'appointment_day' => Carbon::today()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'confirmé', // Ensure status is 'confirmé'
        ]);

    }
}
