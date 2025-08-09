<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Truck extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'company_id',
        'license_plate',
        'identification_number',
        'next_major_inspection',
        'next_safety_inspection',
        'next_tachograph_inspection',
        'additional_information',
    ];

    /**
     * Get the company that the truck belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
