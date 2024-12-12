<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchedClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'indentifier',
        'claim_id',
        'date',
        'processing_cost',
        'insurer_id',
        'total_amount',
    ];
}
