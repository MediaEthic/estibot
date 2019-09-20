<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quotation_id',
        'quantity',
        'time',
        'weight',
        'margin',
        'cost',
        'thousand',
        'shipping',
        'vat_price',
        'price',
    ];


    /**
     * Get the quotation of the quantity.
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
