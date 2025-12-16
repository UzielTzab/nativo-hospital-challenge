<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('patients')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Patient::create([
            'name' => 'Carlos Alberto',
            'paternal_surname' => 'Sánchez',
            'maternal_surname' => 'Ramírez',
            'origin_city' => 'Mérida',
            'admission_date' => '2023-10-01',
            'sex' => 'M',
            'birth_date' => '2015-03-12',
            'tutor_id' => 1,
            'hospital_id' => 1,
        ]);

        Patient::create([
            'name' => 'Sofía Isabel',
            'paternal_surname' => 'Torres',
            'maternal_surname' => 'Gómez',
            'origin_city' => 'Cancún',
            'admission_date' => '2023-11-15',
            'sex' => 'F',
            'birth_date' => '2016-07-22',
            'tutor_id' => 2,
            'hospital_id' => 1,
        ]);

        Patient::create([
            'name' => 'Miguel Ángel',
            'paternal_surname' => 'Pérez',
            'maternal_surname' => 'Martínez',
            'origin_city' => 'Playa del Carmen',
            'admission_date' => '2024-01-20',
            'sex' => 'M',
            'birth_date' => '2017-05-18',
            'tutor_id' => 3,
            'hospital_id' => 2,
        ]);

        Patient::create([
            'name' => 'Ana María',
            'paternal_surname' => 'López',
            'maternal_surname' => 'Hernández',
            'origin_city' => 'Valladolid',
            'admission_date' => '2024-02-10',
            'sex' => 'F',
            'birth_date' => '2018-09-25',
            'tutor_id' => 4,
            'hospital_id' => 2,
        ]);

        Patient::create([
            'name' => 'Luis Fernando',
            'paternal_surname' => 'García',
            'maternal_surname' => 'Rodríguez',
            'origin_city' => 'Tulum',
            'admission_date' => '2024-03-05',
            'sex' => 'M',
            'birth_date' => '2019-11-30',
            'tutor_id' => 5,
            'hospital_id' => 3,
        ]);
    }
}