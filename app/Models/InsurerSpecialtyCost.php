<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsurerSpecialtyCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'insurer_id',
        'specialty_id',
        'percent_cost',
    ];
}
