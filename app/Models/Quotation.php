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
        'user_id',
        'user_name',
        'user_surname',
        'description',
        'image',
        'third_type',
        'third_id',
        'third_name',
        'contact_ethic',
        'contact_id',
        'contact_civility',
        'contact_name',
        'contact_surname',
        'contact_email',
        'label_type',
        'label_id',
        'delivery_date',
        'duration_number',
        'duration_format',
        'validity',
        'settlement_id',
        'cost',
        'thousand',
        'quantity',
        'shipping',
        'vat',
        'vat_price',
        'price',
        'workflow',
        'datas_price',
        'status_id',
    ];


    /**
     * Get the user of the quotation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
     * Get the settlement of the quotation.
     */
    public function settlement()
    {
        return $this->belongsTo(Settlement::class);
    }

    /**
     * Get the status of the quotation.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get all of the quantities for the quotation.
     */
    public function quantities()
    {
        return $this->hasMany(Quantity::class);
    }
}
