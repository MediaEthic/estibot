<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finishing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'printing_id',
        'name',
        'consumable',
        'makeready_times',
        'cadence',
        'overlay_sheet',
    ];


    /**
     * Get the printing of the finishing.
     */
    public function printing()
    {
        return $this->belongsTo(Printing::class);
    }
}
