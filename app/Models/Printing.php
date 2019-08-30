<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'maker',
        'name',
        'number_units',
        'number_colors',
        'size_paperminx',
        'size_paperminy',
        'size_papermaxx',
        'size_papermaxy',
        'printable_areax',
        'printable_areay',
        'weight_minimum',
        'weight_maximum',
        'thickness_minimum',
        'thickness_maximum',
        'makeready_times',
        'cadence',
        'hourly_rate',
        'overlay_sheet',
        'wastage',
        'active',
    ];
}
