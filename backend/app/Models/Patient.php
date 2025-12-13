<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    Use HasFactory;

    protected $fillable = [
        'name',
        'paternal_surname',
        'maternal_surname',
        'birth_date',
        'sex',
        'origin_city',
        'admission_date',
        'hospital_id',
        'tutor_id', 
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }   

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
