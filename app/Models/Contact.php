<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'third_id',
        'civility',
        'name',
        'surname',
        'profession',
        'email',
        'mobile',
        'phone',
        'default',
        'active',
    ];


    /**
     * Get the third of the contact.
     */
    public function third()
    {
        return $this->belongsTo(Third::class);
    }

    /**
     * Get all of the quotations for the contact.
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
