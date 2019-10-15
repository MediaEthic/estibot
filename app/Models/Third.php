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
        'address_line1',
        'address_line2',
        'address_line3',
        'zipcode',
        'city',
        'country_id',
    ];


    /**
     * Set the third's address_line2.
     *
     * @param  string  $value
     * @return void
     */
    public function setAdressLine2Attribute($value)
    {
        $this->attributes['address_line2'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Set the third's address_line3.
     *
     * @param  string  $value
     * @return void
     */
    public function setAdressLine3Attribute($value)
    {
        $this->attributes['address_line3'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Set the third's zipcode.
     *
     * @param  string  $value
     * @return void
     */
    public function setZipcodeAttribute($value)
    {
        $this->attributes['zipcode'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Set the third's city.
     *
     * @param  string  $value
     * @return void
     */
    public function setCityAttribute($value)
    {
        $this->attributes['city'] = mb_strtoupper($value, 'UTF-8');
    }

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
