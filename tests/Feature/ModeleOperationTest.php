<?php

namespace Tests\Unit;

use App\Models\garage;
use App\Models\Voiture;
use App\Models\Operation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModeleOperationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_has_correct_fillable_attributes()
    {
        $operation = new Operation();
        $this->assertEquals(
            [
                'categorie',
                'nom',
                'description',
                'date_operation',
                'photo',
                'voiture_id',
                'garage_id',
                'autre_operation',
                'kilometrage',  // Added this field
                'create_by'
            ],
            $operation->getFillable()
        );
    }

    /** @test */
    public function it_belongs_to_a_voiture()
    {
        $voiture = Voiture::factory()->create();
        $garage = garage::create(
            [
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
            ]
        );

        $operation = Operation::factory()->create([
            'voiture_id' => $voiture->id,
            'garage_id' => $garage->id,
            'kilometrage' => 10000 // Added required kilometrage field
        ]);

        $this->assertTrue($operation->voiture->is($voiture));
    }
}
