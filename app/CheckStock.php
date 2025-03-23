<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckStock extends Model
{
    protected $table = 'check_stocks';

    protected $fillable = [
        'material_id',
        'stock_current',
        'quantity_items',
        'quantity_entries',
        'quantity_outputs',
        'full_name',
        'date_check',
        'isDesface'
    ];

    public $timestamps = true;

    public function material()
    {
        return $this->belongsTo('App\Material');
    }
}
