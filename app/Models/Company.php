<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

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
        'user_id',
        'name',
        'phone',
        'email',
        'street',
        'house_number',
        'city',
        'state',
        'zip',
        'country',
        'website',
        'authorization_number',
        'authorization_number_expiry_date',
    ];

    /**
     * Get the user that owns this company.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the drivers that belong to this company.
     */
    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    /**
     * Get the trucks that belong to this company.
     */
    public function trucks()
    {
        return $this->hasMany(Truck::class);
    }

    /**
     * Get the trailers that belong to this company.
     */
    public function trailers()
    {
        return $this->hasMany(Trailer::class);
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
