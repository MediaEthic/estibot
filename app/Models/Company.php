<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logo',
        'phone',
        'email',
        'address_line1',
        'address_line2',
        'address_line3',
        'zipcode',
        'city',
        'country_id',
        'language_id',
        'time_zone_id',
        'legal_form',
        'capital',
        'register',
        'siret',
        'tva',
        'settlement_id',
        'duration_number',
        'duration_format',
        'head_quotation',
        'foot_quotation',
        'signature_quotation',
        'subject_email',
        'body_email',
        'twitter',
        'facebook',
        'gplus',
        'linkedin',
        'instagram',
        'dribble',
        'youtube',
        'vimeo',
        'github',
        'blog',
        'prepress',
        'winder',
        'api_url',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Set the company's address_line2.
     *
     * @param  string  $value
     * @return void
     */
    public function setAdressLine2Attribute($value)
    {
        $this->attributes['address_line2'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Set the company's address_line3.
     *
     * @param  string  $value
     * @return void
     */
    public function setAdressLine3Attribute($value)
    {
        $this->attributes['address_line3'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Set the company's zipcode.
     *
     * @param  string  $value
     * @return void
     */
    public function setZipcodeAttribute($value)
    {
        $this->attributes['zipcode'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Set the company's city.
     *
     * @param  string  $value
     * @return void
     */
    public function setCityAttribute($value)
    {
        $this->attributes['city'] = mb_strtoupper($value, 'UTF-8');
    }


    /**
     * Get the country of the company.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the language of the company.
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Get the timezone of the company.
     */
    public function timezone()
    {
        return $this->belongsTo(TimeZone::class);
    }

    /**
     * Get the settlement of the company.
     */
    public function settlement()
    {
        return $this->belongsTo(Settlement::class);
    }

    /**
     * Get all of the quotations for the user.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
