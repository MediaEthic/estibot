<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'third_type',
        'third_id',
        'contact_id',
        'label_type',
        'label_id',
        'delivery_date',
        'validity',
        'cost',
        'thousand',
        'quantity',
        'shipping',
        'vat',
        'vat_price',
        'price',
        'workflow',
        'datas_price',
    ];


    /**
     * Get the third of the quotation.
     */
    public function third()
    {
        return $this->belongsTo(Third::class);
    }

    /**
     * Get the contact of the quotation.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the label of the quotation.
     */
    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    /**
     * Get all of the quantities for the quotation.
     */
    public function quantities()
    {
        return $this->hasMany(Quantity::class);
    }
}
