<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Truck extends Model
{
    use HasFactory, Notifiable;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    protected $fillable = [
        'company_id',
        'license_plate',
        'identification_number',
        'next_major_inspection',
        'next_safety_inspection',
        'next_tachograph_inspection',
        'additional_information',
        'assigned_to_trailer',
        'assigned_to_driver',
        'is_active',
    ];

    /**
     * Get the company that the truck belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Boot the model and auto-generate UUIDs.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }
}
