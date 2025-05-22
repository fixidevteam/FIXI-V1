<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;
use App\Models\Voiture;
use App\Models\Garage;
use Carbon\Carbon;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $voitureIds = Voiture::pluck('id')->toArray();
        $garageIds = Garage::pluck('id')->toArray();

        // Seed 20 visit records
        foreach (range(1, 20) as $i) {
            Visit::create([
                'date' => Carbon::now()->subDays(rand(0, 365))->format('Y-m-d'),
                'voiture_id' => $voitureIds[array_rand($voitureIds)],
                'garage_id' => $garageIds[array_rand($garageIds)],
            ]);
        }
    }
}