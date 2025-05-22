<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i <= 10; $i++) {
            Appointment::create([
                'user_full_name' => 'User ' . ($i + 1),
                'user_phone' => '0632297361',
                'user_email' => 'user@gmail.com',
                'garage_ref' => 'GAR-00001',
                'appointment_day' => '2025-05-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
                'appointment_time' => rand(9, 17) . ':' . (rand(0, 1) ? '00' : '30'),
                'status' => rand(0, 1) ? 'confirmé' : 'annulé',
                'categorie_de_service' => 'x' 
            ]);
        }
    }
}