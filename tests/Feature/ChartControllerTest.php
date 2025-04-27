<?php

namespace Tests\Feature\Mechanic;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Garage;
use App\Models\Mechanic;
use App\Models\Operation;
use App\Models\Voiture;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChartControllerTest extends TestCase
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

        // Create a mechanic
        $this->mechanic = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true, // Ensure the mechanic is active
        ]);
        // Create a voiture
        $this->voiture = Voiture::create([
            'user_id' => $this->user->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
            'photo' => 'photo.jpg',
        ]);
        // Authenticate as the mechanic
        $this->actingAs($this->mechanic, 'mechanic');
    }

    /** @test */
    public function it_displays_chart_data_for_the_selected_year()
    {
        // Create operations for the garage
        $operation1 = Operation::create([
            'garage_id' => $this->garage->id,
            'date_operation' => Carbon::create(2023, 1, 15)->toDateString(), // January 2023
            'description' => 'Oil Change',
            'categorie' => 'Maintenance',
            'voiture_id' => $this->voiture->id,
        ]);

        $operation2 = Operation::create([
            'garage_id' => $this->garage->id,
            'date_operation' => Carbon::create(2023, 2, 20)->toDateString(), // February 2023
            'description' => 'Tire Rotation',
            'categorie' => 'Maintenance',
            'voiture_id' => $this->voiture->id,
        ]);

        $operation3 = Operation::create([
            'garage_id' => $this->garage->id,
            'date_operation' => Carbon::create(2024, 1, 10)->toDateString(), // January 2024 (different year)
            'description' => 'Brake Repair',
            'categorie' => 'Repair',
            'voiture_id' => $this->voiture->id,
        ]);

        // Make a request to the chart route for the year 2023
        $response = $this->get(route('mechanic.chart', ['year' => 2023]));

        // Assert the response status and view
        $response->assertStatus(200)
            ->assertViewIs('mechanic.chart.index')
            ->assertViewHas('labels')
            ->assertViewHas('values')
            ->assertViewHas('years')
            ->assertViewHas('selectedYear', 2023);

        // Get the view data
        $viewData = $response->viewData('labels');
        $values = $response->viewData('values');
        $years = $response->viewData('years');

        // Assert the labels (French month names)
        $this->assertEquals([
            'janvier',
            'février',
            'mars',
            'avril',
            'mai',
            'juin',
            'juillet',
            'août',
            'septembre',
            'octobre',
            'novembre',
            'décembre'
        ], $viewData);

        // Assert the values (operation counts for each month)
        $this->assertEquals([
            1,
            1,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0 // 1 operation in January, 1 in February, 0 in others
        ], $values);

        // Assert the available years
        $this->assertEquals([2023, 2024], $years->toArray());
    }

    /** @test */
    public function it_displays_chart_data_for_the_current_year_if_no_year_is_selected()
    {
        // Create an operation for the current year
        $operation = Operation::create([
            'garage_id' => $this->garage->id,
            'date_operation' => now()->toDateString(), // Current year
            'description' => 'Oil Change',
            'categorie' => 'Maintenance',
            'voiture_id' => $this->voiture->id,
        ]);

        // Make a request to the chart route without specifying a year
        $response = $this->get(route('mechanic.chart'));

        // Assert the response status and view
        $response->assertStatus(200)
            ->assertViewIs('mechanic.chart.index')
            ->assertViewHas('selectedYear', now()->year);
    }
}
