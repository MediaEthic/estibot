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
        'plate',
        'makeready_times',
        'makeready_times_color',
        'unit_cadence',
        'cadence',
        'hourly_rate',
        'overlay_sheet',
        'overlay_sheet_color',
        'wastage',
        'active',
    ];

    /**
     * Get all of the labels for the printing.
     */
    public function labels()
    {
        return $this->hasMany(Label::class);
    }

    /**
     * Get all of the finishings for the printing.
     */
    public function finishings()
    {
        return $this->hasMany(Finishing::class);
    }
}
