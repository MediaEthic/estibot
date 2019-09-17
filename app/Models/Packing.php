<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'makeready_times',
        'unit_cadence',
        'cadence',
        'duration',
        'hourly_rate',
        'active',
    ];
}
