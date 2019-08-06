<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
    ];


    /**
     * Get all of the thirds for the country.
     */
    public function thirds()
    {
        return $this->hasMany(Third::class);
    }
}
