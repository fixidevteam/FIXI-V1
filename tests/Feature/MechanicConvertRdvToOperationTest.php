<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Garage;
use App\Models\Appointment;
use App\Models\Voiture;
use App\Models\MarqueVoiture;
use App\Models\Mechanic;
use App\Models\Operation;

class MechanicConvertRdvToOperationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->mechanic = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true,
        ]);

        $this->client = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'telephone' => '0712345678',
            'status' => 1,
        ]);

        $this->voiture = Voiture::create([
            'user_id' => $this->client->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
        ]);

        $this->marque1 = MarqueVoiture::create(['marque' => 'Toyota']);
        $this->marque2 = MarqueVoiture::create(['marque' => 'Honda']);

        $this->appointment = Appointment::create([
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'John Doe',
            'user_phone' => '0612345678',
            'user_email' => 'client@example.com',
            'appointment_day' => now()->format('Y-m-d'),
            'appointment_time' => '10:00:00',
            'categorie_de_service' => 'Oil Change',
            'status' => 'confirmé',
        ]);

        $this->actingAs($this->mechanic, 'mechanic');
    }

    /** @test */
    public function it_shows_convert_form_for_existing_client()
    {
        $response = $this->get("/fixi-pro/conversion/{$this->appointment->id}");

        $response->assertStatus(200)
            ->assertViewIs('mechanic.convertRdvToOperation.convert')
            ->assertViewHas('Appointment')
            ->assertViewHas('client')
            ->assertViewHas('marques');
    }

    /** @test */
    public function it_shows_convert_form_for_non_existing_client()
    {
        $appointment = Appointment::create([
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'New Client',
            'user_phone' => '0612345679',
            'appointment_day' => now()->format('Y-m-d'),
            'appointment_time' => '11:00:00',
            'categorie_de_service' => 'Tire Rotation',
            'status' => 'confirmé',
        ]);

        $response = $this->get("/fixi-pro/conversion/{$appointment->id}");

        $response->assertStatus(200)
            ->assertViewHas('client', null);
    }

    /** @test */
    public function it_converts_appointment_with_existing_voiture()
    {
        $data = [
            'appointment_id' => $this->appointment->id,
            'voiture_id' => $this->voiture->id,
            'categorie' => 'Oil Change',
            'description' => 'Regular maintenance',
            'date_operation' => now()->format('Y-m-d'),
            'client_email' => 'client@example.com',
        ];

        $response = $this->post("/fixi-pro/conversion", $data);

        $response->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('operations', [
            'voiture_id' => $this->voiture->id,
            'garage_id' => $this->garage->id,
            'categorie' => 'Oil Change',
        ]);
    }

    /** @test */
    public function it_converts_appointment_with_new_voiture()
    {
        $data = [
            'appointment_id' => $this->appointment->id,
            'part1' => '54321',
            'part2' => 'B',
            'part3' => '89',
            'marque' => 'Honda',
            'modele' => 'Civic',
            'categorie' => 'Brake Repair',
            'description' => 'Brake system check',
            'date_operation' => now()->format('Y-m-d'),
            'client_name' => 'New Client',
            'client_tel' => '0612345679',
        ];

        $response = $this->post("/fixi-pro/conversion", $data);

        $response->assertRedirect()
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('voitures', [
            'numero_immatriculation' => '54321-B-89',
            'marque' => 'Honda',
            'modele' => 'Civic',
        ]);

        $this->assertDatabaseHas('operations', [
            'categorie' => 'Brake Repair',
            'garage_id' => $this->garage->id,
        ]);
    }

    /** @test */
    public function it_creates_new_client_when_not_exists()
    {
        $appointment = Appointment::create([
            'garage_ref' => $this->garage->ref,
            'user_full_name' => 'New Client',
            'user_phone' => '0612345679',
            'appointment_day' => now()->format('Y-m-d'),
            'appointment_time' => '11:00:00',
            'categorie_de_service' => 'Tire Rotation',
            'status' => 'confirmé',
        ]);

        $data = [
            'appointment_id' => $appointment->id,
            'part1' => '98765',
            'part2' => 'C',
            'part3' => '12',
            'marque' => 'Toyota',
            'modele' => 'Camry',
            'categorie' => 'Inspection',
            'description' => 'Annual inspection',
            'date_operation' => now()->format('Y-m-d'),
            'client_name' => 'New Client',
            'client_tel' => '0612345679',
        ];

        $response = $this->post("/fixi-pro/conversion", $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'name' => 'New Client',
            'telephone' => '0612345679',
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $data = [
            'appointment_id' => $this->appointment->id,
            'categorie' => 'Oil Change',
            'date_operation' => now()->format('Y-m-d'),
        ];

        $response = $this->post("/fixi-pro/conversion", $data);

        $response->assertSessionHasErrors([
            'part1',
            'part2',
            'part3',
            'marque',
            'modele',
        ]);
    }
}
