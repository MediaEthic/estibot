<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Third extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'address',
        'zipcode',
        'city',
        'country_id',
    ];


    /**
     * Get the country of the third.
     */
    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    /**
     * Get all of the contacts for the third.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Get all of the quotations for the third.
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
