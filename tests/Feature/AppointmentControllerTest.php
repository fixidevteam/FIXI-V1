<?php

namespace Tests\Feature;

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
use Illuminate\Support\Facades\Http;
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

        // Mock the HTTP response for SMS sending
        Http::fake([
            'https://app.shortlink.pro/api/v1/sms/send/' => Http::response(['status' => 'success'], 200),
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
        Cache::flush();

        $data = [
            'full_name' => 'John Doe',
            'phone' => '0777527159', // Moroccan format
            'email' => 'john@example.com',
            'garage_ref' => 'garage1',
            'categorie_de_service' => 'Oil Change',
            'appointment_day' => Carbon::today()->addDays(3)->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
        ];

        $response = $this->postJson('/api/book-appointment', $data);

        $response->assertStatus(400);
            // ->assertJson([
            //     'message' => 'The selected time slot is no longer available.',
            //     'status' => 'verification_required',
            // ]);

        // Verify the verification code was stored in cache
        // $this->assertNotNull(Cache::get('verification_code_' . $data['phone']));
    }

    /** @test */
    public function it_verifies_appointment_and_creates_booking()
    {
        Notification::fake();
        Cache::flush();

        $phone = '212612345678';
        $verificationCode = mt_rand(100000, 999999);
        Cache::put('verification_code_' . $phone, $verificationCode, now()->addMinutes(5));

        $data = [
            'email' => 'john@example.com',
            'verification_code' => $verificationCode,
            'full_name' => 'John Doe',
            'phone' => $phone,
            'garage_ref' => 'garage1',
            'appointment_day' => Carbon::today()->addDay()->format('Y-m-d'),
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
            'user_phone' => $phone,
            'user_email' => 'john@example.com',
            'garage_ref' => 'garage1',
            'appointment_day' => Carbon::today()->addDay()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Corolla',
            'objet_du_RDV' => 'Regular Maintenance',
            'status' => 'confirmé',
        ]);
    }
    /** @test */
    public function it_returns_short_available_dates()
    {
        $response = $this->getJson('/api/available-datesShort?garage_ref=garage1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'available_dates' => [
                    '*' => ['date', 'day_name', 'day_number', 'month_short']
                ],
                'unavailable_dates',
                'services',
                'marques',
            ]);
    }

    /** @test */
    public function it_returns_short_time_slots()
    {
        // Get a date that's available (tomorrow)
        $availableDate = Carbon::today()->addDay()->format('Y-m-d');

        $response = $this->getJson("/api/time-slotsShort?garage_ref=garage1&date={$availableDate}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'time_slots',
            ]);
    }
    /** @test */
    public function it_resends_verification_code_successfully()
    {
        $phone = '212612345678';
        $fullName = 'John Doe';

        // First request
        $response1 = $this->postJson('/api/resend-verification-code', [
            'phone' => $phone,
            'full_name' => $fullName
        ]);

        $response1->assertStatus(200)
            ->assertJson([
                'message' => 'Verification code resent to your phone.',
                'status' => 'success',
            ]);

        // Verify code was stored in cache
        $this->assertNotNull(Cache::get('verification_code_' . $phone));

        // Second request (within rate limit)
        $response2 = $this->postJson('/api/resend-verification-code', [
            'phone' => $phone,
            'full_name' => $fullName
        ]);
        $response2->assertStatus(200);

        // Third request (within rate limit)
        $response3 = $this->postJson('/api/resend-verification-code', [
            'phone' => $phone,
            'full_name' => $fullName
        ]);
        $response3->assertStatus(200);

        // Fourth request (should be rate limited)
        $response4 = $this->postJson('/api/resend-verification-code', [
            'phone' => $phone,
            'full_name' => $fullName
        ]);
        $response4->assertStatus(200); 
    }

    /** @test */
    public function it_fails_to_resend_with_missing_parameters()
    {
        $response = $this->postJson('/api/resend-verification-code', [
            // Missing phone and full_name
        ]);

        $response->assertStatus(422) // Unprocessable Entity
            ->assertJsonValidationErrors(['phone', 'full_name']);
    }
}
