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
        'daily_processing_capacity',
        'maximum_batch_size',
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