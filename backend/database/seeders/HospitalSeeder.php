<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Hospital;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('hospitals')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Hospital::create([
            'name'=>'Hospital Materno Infantil',    
            'city'=>'Mérida',
        ]);

        Hospital::create([
            'name' => 'Hospital General Zona Norte',
            'city' => 'Cancún',
        ]);

        Hospital::create([
            'name' => 'Clínica Pediátrica San Lucas',
            'city' => 'Ciudad de México',
        ]);

    }
}
