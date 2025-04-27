<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Mechanic;
use App\Models\Garage;
use App\Models\Voiture;
use App\Models\Operation;
use App\Models\nom_categorie;
use App\Models\nom_operation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OperationsExport;
use App\Models\nom_sous_operation;
use App\Models\User;

class ExportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Garage $garage;
    protected Mechanic $mechanic;
    protected Voiture $voiture;
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


        // Create a mechanic
        $this->mechanic = Mechanic::create([
            'name' => 'Mechanic User',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'garage_id' => $this->garage->id,
            'telephone' => '0612345678',
            'status' => true,
        ]);
        // Create a user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'ville' => 'Marrakech',
            'telephone' => '0612345678',
            'status' => 1,
        ]);
        // Create a voiture
        $this->voiture = Voiture::create([
            'user_id' => $this->user->id,
            'marque' => 'Toyota',
            'modele' => 'Corolla',
            'numero_immatriculation' => '12345-A-67',
        ]);
        $this->categorie = nom_categorie::create([
            'nom_categorie' => 'Test Category',
        ]);

        // Create a nom_operation
        $this->operation = nom_operation::create([
            'nom_operation' => 'Test Operation',
            'nom_categorie_id' => $this->categorie->id,
        ]);

        // Create a nom_sous_operation
        $this->sousOperation = nom_sous_operation::create([
            'nom_sous_operation' => 'Test Sous Operation',
            'nom_operation_id' => $this->operation->id,
        ]);

        $this->actingAs($this->mechanic, 'mechanic');
    }

    /** @test */
    public function it_exports_operations_successfully()
    {

        $operation = Operation::create([
            'garage_id' => $this->garage->id,
            'voiture_id' => $this->voiture->id,
            'categorie' => $this->categorie->id,
            'nom' => 'Oil Change',
            'description' => 'Regular oil change',
            'date_operation' => now()->format('Y-m-d'),
            'kilometrage' => 10000,
            'create_by' => 'mechanic',
        ]);

        Excel::fake();

        $response = $this->get("/fixi-pro/mechanic/voitures/export/{$this->voiture->id}");

        $response->assertStatus(200);

        Excel::assertDownloaded("Suivi_Operations_1234-XYZ.xlsx", function (OperationsExport $export) {
            return true; // You can go deeper into export content if needed
        });
    }

    /** @test */
    public function it_returns_error_when_no_operations_found()
    {
        $response = $this->get("/fixi-pro/mechanic/voitures/export/{$this->voiture->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Aucune opération trouvée pour le véhicule sélectionné.');
    }

    /** @test */
    public function it_returns_error_when_voiture_not_found()
    {
        // Remove the voiture
        $this->voiture->delete();

        $response = $this->get("/fixi-pro/mechanic/voitures/export/9999");

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Aucune opération trouvée pour le véhicule sélectionné.');
    }
}
