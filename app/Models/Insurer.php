<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurer extends Model
{
    use HasFactory;

    protected $table = 'insurers';

    protected $fillable = [
        'name',
        'code',
        'email',
        'minimum_batch_size',
        'maximum_batch_size',
        'daily_processing_capacity',
        'batching_date_type',
    ];

    public function specialty_costs()
    {
        return $this->hasMany(InsurerSpecialtyCost::class);
    }

    public function priority_costs()
    {
        return $this->hasMany(InsurerPriorityCost::class);
    }
} 