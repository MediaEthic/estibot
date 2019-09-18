<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishingLabel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'finishing_id',
        'label_id',
        'shape',
        'reworking',
    ];


    /**
     * Get the finishing.
     */
    public function finishing()
    {
        return $this->belongsTo(Finishing::class);
    }

    /**
     * Get the label.
     */
    public function label()
    {
        return $this->belongsTo(Label::class);
    }
}
