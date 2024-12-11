<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $table = 'claims';
    
    public $timestamps = false;

    protected $fillable = [
        'insurer_id',
        'provider_id',
        'specialty_id',
        'priority_level',
        'encounter_date',
        'submission_date',
        'total_amount',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->submission_date = now()->format('Y-m-d');
        });
    }

    public function claim_items() {
        return $this->hasMany(ClaimItem::class);
    }

    public function insurer() {
        return $this->belongsTo(Insurer::class);
    }

    public function provider() {
        return $this->belongsTo(Provider::class);
    }

    public function specialty() {
        return $this->belongsTo(Specialty::class);
    }
} 