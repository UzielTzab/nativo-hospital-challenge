<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hospital extends Model
{
    use Hasfactory;

    protected $fillable = [
        'name',
        'city',
    ];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
    
}
