<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label_id',
        'quantity',
        'models',
        'plates',
        'price',
        'shipping',
        'vat',
        'operations',
    ];


    /**
     * Get the label of the model.
     */
    public function label()
    {
        return $this->belongsTo(Label::class);
    }
}
