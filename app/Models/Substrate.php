<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Substrate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'weight',
        'thickness',
        'width',
        'length',
        'price',
        'active',
    ];

    /**
     * Get all of the labels for the substrate.
     */
    public function labels()
    {
        return $this->hasMany(Label::class);
    }
}
