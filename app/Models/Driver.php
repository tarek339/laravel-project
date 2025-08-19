<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Driver extends Model
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
        'first_name',
        'last_name',
        'email',
        'phone',
        'license_number',
        'license_expiry_date',
        'driver_card_number',
        'driver_card_expiry_date',
        'driver_qualification_number',
        'driver_qualification_expiry_date',
        'street',
        'house_number',
        'city',
        'postal_code',
        'state',
        'country',
        'additional_information',
        'assigned_to',
        'is_active',
    ];

    /**
     * Get the company that the driver belongs to.
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
