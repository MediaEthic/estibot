<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'third_type',
        'third_id',
        'name',
        'width',
        'length',
        'printing_id',
        'number_colors',
        'quadri',
        'substrate_type',
        'substrate_id',
        'cutting_type',
        'cutting_id',
        'winding',
        'packing',
    ];


    /**
     * Get the printing of the label.
     */
    public function printing()
    {
        return $this->belongsTo(Printing::class);
    }

    /**
     * Get the substrate of the label.
     */
    public function substrate()
    {
        return $this->belongsTo(Substrate::class);
    }

    /**
     * Get the cutting of the label.
     */
    public function cutting()
    {
        return $this->belongsTo(Cutting::class);
    }


    /**
     * Get all of the quotations for the label.
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * The finishings that belong to the label.
     */
    public function finishings()
    {
        return $this->belongsToMany(Finishing::class);
    }
}
