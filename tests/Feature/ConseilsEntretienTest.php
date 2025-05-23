<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voiture;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ConseilsEntretienTest extends TestCase
{
    /**
     * A basic feature test example.
     */    use RefreshDatabase;
    public function test_conseils_entretien()
    {
        $user = User::factory()->create(['status' => 1, 'ville' => 'marrakech']);
        $voiture = Voiture::create([
            'marque' => 'Dacia',
            'modele' => 'Corolla',
            'numero_immatriculation' => '123-أ-45',
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($user)->get(route('voiture.show', $voiture->id));
        $response->assertViewIs('userCars.show');
        $response->assertOk();
    }
    public function test_get_Conseils(): void
    {
        $user = User::factory()->create(['status' => 1, 'ville' => 'marrakech']);
        $voiture = Voiture::create([
            'marque' => 'Dacia',
            'modele' => 'Corolla',
            'numero_immatriculation' => '123-أ-45',
            'user_id' => $user->id,
        ]);
        // Mock the external HTTP response
        Http::fake([
            'https://fixi.ma/marques-de-voitures/Dacia' => Http::response('Success', 200),
        ]);

        // Make the fake HTTP request
        $response = Http::get("https://fixi.ma/marques-de-voitures/Dacia");
        // check if the response status is 200 
        $this->assertEquals(200, $response->getStatusCode());
    }
}
