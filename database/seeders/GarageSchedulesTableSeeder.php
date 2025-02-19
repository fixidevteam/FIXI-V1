<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GarageSchedulesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('garage_schedules')->insert([
            [
                'garage_ref' => 'GAR-00001',
                'available_day' => '0',
                'available_from' => '09:00',
                'available_to' => '12:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'garage_ref' => 'GAR-00001',
                'available_day' => '1',
                'available_from' => '14:00',
                'available_to' => '17:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'garage_ref' => 'GAR-00001',
                'available_day' => '2',
                'available_from' => '10:00',
                'available_to' => '13:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'garage_ref' => 'GAR-00002',
                'available_day' => '0',
                'available_from' => '08:00',
                'available_to' => '11:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'garage_ref' => 'GAR-00003',
                'available_day' => '1',
                'available_from' => '13:00',
                'available_to' => '16:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}