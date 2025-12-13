<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Tutor;

class TutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('tutors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Tutor::create([
            'name' => 'Juana Pérez García',
            'phone' => '9991234567',
        ]);
        
        Tutor::create([
            'name' => 'Roberto López Hernández',
            'phone' => '5551234567',
        ]);

        Tutor::create([
            'name' => 'María Elena Torres',
            'phone' => '8181234567',
        ]);

        Tutor::create([
            'name' => 'Francisco Javier Gómez',
            'phone' => '6641234567',
        ]);

        Tutor::create([
            'name' => 'Ana Luisa Martínez',
            'phone' => '4491234567',
        ]);
    }
}
