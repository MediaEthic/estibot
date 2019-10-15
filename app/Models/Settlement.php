<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settlements';

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
        'payment_within',
        'payment_on',
    ];


    /**
     * Get all of the quotations for the settlement.
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
