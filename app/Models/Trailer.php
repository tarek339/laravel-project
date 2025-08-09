<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Trailer extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'company_id',
        'license_plate',
        'identification_number',
        'next_major_inspection',
        'next_safety_inspection',
        'additional_information',
    ];

    /**
     * Get the company that the trailer belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
