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
        'name',
        'width',
        'length',
        'substrate_id',
        'winding',
    ];


    /**
     * Get the substrate of the label.
     */
    public function substrate()
    {
        return $this->belongsTo(Substrate::class);
    }

    /**
     * Get all of the models for the label.
     */
    public function models()
    {
        return $this->hasMany(Copy::class);
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
