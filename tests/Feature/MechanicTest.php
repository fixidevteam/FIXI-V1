<?php

namespace Tests\Unit;

use App\Models\Mechanic;
use App\Models\Garage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MechanicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_has_correct_fillable_attributes()
    {
        $mechanic = new Mechanic();
        $this->assertEquals(
            ['name', 'email', 'password', 'garage_id', 'telephone', 'status'],
            $mechanic->getFillable()
        );
    }

    /** @test */
    public function test_it_hides_correct_attributes()
    {
        $garage = Garage::create([
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

        // Create a mechanic associated with the garage
        $mechanic = Mechanic::create([
            'name' => 'Test Mechanic',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'garage_id' => $garage->id, // Mechanic is linked to the garage
        ]);
        $hiddenAttributes = $mechanic->getHidden();

        $this->assertContains('password', $hiddenAttributes);
        $this->assertContains('remember_token', $hiddenAttributes);
    }

    /** @test */
    public function test_it_belongs_to_a_garage()
    {
        $garage = Garage::create([
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

        // Create a mechanic associated with the garage
        $mechanic = Mechanic::create([
            'name' => 'Test Mechanic',
            'email' => 'mechanic@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'garage_id' => $garage->id, // Mechanic is linked to the garage
        ]);

        $this->assertTrue($mechanic->garage->is($garage));
    }

    /** @test */
}
