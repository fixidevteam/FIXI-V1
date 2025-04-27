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

class DisponibiliteRDVTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_it_returns_available_time_slots()
    {
        // Create a user
        $user = User::factory()->create(['status' => 1, 'ville' => 'marrakech']);

        // Create garage
        $garage = Garage::create([
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

        // Create an appointment at 10 AM (booked)
        Appointment::create([
            'user_full_name' => $user->name,
            'user_phone' => '1234567890',
            'user_email' => $user->email,
            'garage_ref' => $garage->ref,
            'categorie_de_service' => 'Oil Change',
            'modele' => 'Toyota Corolla',
            'numero_immatriculation' => 'ABC-1234',
            'objet_du_RDV' => 'Routine check-up',
            'appointment_day' => $selectedDate,
            'appointment_time' => '10:00:00',
            'status' => 'en cours', // Active booking
        ]);

        // Call the API endpoint
        $response = $this->getJson("/api/time-slots?garage_ref={$garage->ref}&date={$selectedDate}");

        // Assert response is OK
        $response->assertStatus(200);

        // Expected available slots (9 AM - 5 PM, excluding 10 AM booked and 12-1 PM unavailable)
        $expectedSlots = [
            '09:00:00',
            '11:00:00',
            '13:00:00',
            '14:00:00',
            '15:00:00',
            '16:00:00',
        ];

        // Assert JSON structure
        $response->assertJsonStructure(['time_slots']);

        // Assert time slots match expected
        $response->assertJson([
            'time_slots' => $expectedSlots,
        ]);
    }
    public function it_returns_empty_if_no_schedule_found()
    {
        $garage = Garage::create([
            'id' => 2,
            'name' => 'No Schedule Garage',
            'ref' => 'GAR-00002',
            'localisation' => 'Casablanca',
            'ville' => 'Casablanca',
        ]);
        $selectedDate = now()->addDays(5)->toDateString();

        // Call the API without a schedule
        $response = $this->getJson("/api/time-slots?garage_ref={$garage->ref}&date={$selectedDate}");

        // Assert response is OK
        $response->assertStatus(200);

        // Assert empty time slots
        $response->assertJson([
            'time_slots' => [],
        ]);
    }
}
