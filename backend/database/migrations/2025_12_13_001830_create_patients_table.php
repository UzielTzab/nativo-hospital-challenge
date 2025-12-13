<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('paternal_surname');
            $table->string('maternal_surname')->nullable(); 
            $table->date('birth_date'); 
            $table->enum('sex', ['M', 'F']); 
            $table->string('origin_city'); 
            $table->date('admission_date');
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('restrict');
            $table->foreignId('tutor_id')->constrained('tutors')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
