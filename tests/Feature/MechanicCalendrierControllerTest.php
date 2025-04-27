<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Garage;
use App\Models\Mechanic;
use App\Models\GarageSchedule;
use App\Models\GarageUnavailableTime;
use App\Models\jour_indisponible;
use Illuminate\Support\Facades\Auth;

class MechanicCalendrierControllerTest extends TestCase
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
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true,
        ]);

        // Authenticate as the user using the 'mechanic' guard
        $this->actingAs($this->user, 'mechanic');
    }

    /** @test */
    public function it_displays_calendar_with_schedules_and_unavailable_times()
    {
        // Create a schedule for the garage
        $schedule = GarageSchedule::create([
            'garage_ref' => $this->garage->ref,
            'available_day' => 1, // Monday
            'available_from' => '09:00:00',
            'available_to' => '17:00:00',
        ]);

        // Create an unavailable time for the garage
        $unavailableTime = GarageUnavailableTime::create([
            'garage_ref' => $this->garage->ref,
            'unavailable_day' => 1, // Monday
            'unavailable_from' => '12:00:00',
            'unavailable_to' => '13:00:00',
        ]);

        // Create a disabled date for the garage
        $disabledDate = jour_indisponible::create([
            'garage_ref' => $this->garage->ref,
            'date' => now()->addDays(2)->format('Y-m-d'),
        ]);

        // Make the request
        $response = $this->get('/fixi-pro/calendrier');

        // Assert the response
        $response->assertStatus(200)
            ->assertViewIs('mechanic.calendrier.index')
            ->assertViewHas('schedules') // Verify schedules are passed to the view
            ->assertViewHas('unavailableTimes') // Verify unavailable times are passed to the view
            ->assertViewHas('disabledDates'); // Verify disabled dates are passed to the view

        // Verify the schedules data
        $schedules = $response->viewData('schedules');
        $this->assertCount(1, $schedules);
        $this->assertEquals('09:00:00', $schedules->first()->available_from);

        // Verify the unavailable times data
        $unavailableTimes = $response->viewData('unavailableTimes');
        $this->assertCount(1, $unavailableTimes);
        $this->assertEquals('12:00:00', $unavailableTimes->first()->unavailable_from);

        // Verify the disabled dates data
        $disabledDates = $response->viewData('disabledDates');
        $this->assertCount(1, $disabledDates);
        $this->assertEquals(now()->addDays(2)->format('Y-m-d'), $disabledDates->first()->date);
    }

    /** @test */
    public function it_displays_edit_form_for_schedule()
    {
        // Create a schedule for the garage
        $schedule = GarageSchedule::create([
            'garage_ref' => $this->garage->ref,
            'available_day' => 1, // Monday
            'available_from' => '09:00:00',
            'available_to' => '17:00:00',
        ]);

        // Create an unavailable time for the schedule
        $unavailableTime = GarageUnavailableTime::create([
            'garage_ref' => $this->garage->ref,
            'unavailable_day' => 1, // Monday
            'unavailable_from' => '12:00:00',
            'unavailable_to' => '13:00:00',
        ]);

        // Make the request to edit the schedule
        $response = $this->get('/fixi-pro/calendrier/' . $schedule->id . '/edit');

        // Assert the response
        $response->assertStatus(200)
            ->assertViewIs('mechanic.calendrier.edit')
            ->assertViewHas('schedule') // Verify the schedule is passed to the view
            ->assertViewHas('unavailableTimes') // Verify unavailable times are passed to the view
            ->assertViewHas('daysOfWeek'); // Verify days of the week are passed to the view

        // Verify the schedule data
        $scheduleData = $response->viewData('schedule');
        $this->assertEquals($schedule->id, $scheduleData->id);

        // Verify the unavailable times data
        $unavailableTimes = $response->viewData('unavailableTimes');
        $this->assertCount(1, $unavailableTimes);
        $this->assertEquals('12:00:00', $unavailableTimes->first()->unavailable_from);
    }

    /** @test */
    public function it_updates_schedule_and_unavailable_times()
    {
        // Create a schedule for the garage
        $schedule = GarageSchedule::create([
            'garage_ref' => $this->garage->ref,
            'available_day' => 1, // Monday
            'available_from' => '09:00:00',
            'available_to' => '17:00:00',
        ]);

        // Create an unavailable time for the schedule
        $unavailableTime = GarageUnavailableTime::create([
            'garage_ref' => $this->garage->ref,
            'unavailable_day' => 1, // Monday
            'unavailable_from' => '12:00:00',
            'unavailable_to' => '13:00:00',
        ]);

        // New data for the update
        $newData = [
            'available_day' => 2, // Tuesday
            'available_from' => '10:00:00',
            'available_to' => '18:00:00',
            'unavailable_from' => ['14:00:00'],
            'unavailable_to' => ['15:00:00'],
        ];

        // Make the request to update the schedule
        $response = $this->put('/fixi-pro/calendrier/' . $schedule->id, $newData);

        // Assert the response
        $response->assertStatus(302) // Redirects after update
            ->assertRedirect('/fixi-pro/calendrier')
            ->assertSessionHas('success', 'Calendrier mis à jour avec indisponibilités multiples');

        // Verify the schedule was updated
        $updatedSchedule = GarageSchedule::find($schedule->id);
        $this->assertEquals(2, $updatedSchedule->available_day);
        $this->assertEquals('10:00:00', $updatedSchedule->available_from);
        $this->assertEquals('18:00:00', $updatedSchedule->available_to);

        // Verify the old unavailable time was deleted
        // $this->assertNull(GarageUnavailableTime::find($unavailableTime->id));

        // Verify the new unavailable time was created
        $newUnavailableTime = GarageUnavailableTime::where('garage_ref', $this->garage->ref)
            ->where('unavailable_day', 2)
            ->first();
        $this->assertNotNull($newUnavailableTime);
        $this->assertEquals('14:00:00', $newUnavailableTime->unavailable_from);
        $this->assertEquals('15:00:00', $newUnavailableTime->unavailable_to);
    }

    /** @test */
    public function it_handles_update_with_duplicate_schedule_day()
    {
        // Create a schedule for the garage
        $schedule1 = GarageSchedule::create([
            'garage_ref' => $this->garage->ref,
            'available_day' => 1, // Monday
            'available_from' => '09:00:00',
            'available_to' => '17:00:00',
        ]);

        $schedule2 = GarageSchedule::create([
            'garage_ref' => $this->garage->ref,
            'available_day' => 2, // Tuesday
            'available_from' => '10:00:00',
            'available_to' => '18:00:00',
        ]);

        // Try to update schedule1 to the same day as schedule2
        $newData = [
            'available_day' => 2, // Tuesday (conflicts with schedule2)
            'available_from' => '10:00:00',
            'available_to' => '18:00:00',
        ];

        // Make the request to update the schedule
        $response = $this->put('/fixi-pro/calendrier/' . $schedule1->id, $newData);

        // Assert the response
        $response->assertStatus(302) // Redirects back
            ->assertRedirect('/fixi-pro/calendrier')
            ->assertSessionHas('error', 'Un horaire existe déjà pour ce jour.');

        // Verify the schedule was not updated
        $updatedSchedule = GarageSchedule::find($schedule1->id);
        $this->assertEquals(1, $updatedSchedule->available_day); // Still Monday
    }
}
