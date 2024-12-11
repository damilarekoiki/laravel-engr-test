<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'claim_id',
        'name',
        'unit_price',
        'quantity',
        'sub_total'
    ];
}
