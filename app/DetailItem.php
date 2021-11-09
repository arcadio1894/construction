<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description',
        'quantity',
        'default_item_id'
    ];

    public function defaultItem()
    {
        return $this->belongsTo('App\DefaultItem');
    }

    protected $dates = ['deleted_at'];
}
