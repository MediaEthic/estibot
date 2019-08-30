<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cutting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'dimension_width',
        'dimension_length',
        'bleed_width',
        'bleed_length',
        'pose_width',
        'pose_length',
    ];
}
