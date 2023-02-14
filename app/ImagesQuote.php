<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagesQuote extends Model
{
    protected $fillable = [
        'quote_id',
        'description',
        'image',
        'order',
        'type',
        'height',
        'width'
    ];

    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }
}
