<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exampler extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','comment', 'brand_id'];

    public function materials()
    {
        return $this->hasMany('App\Material');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    protected $dates = ['deleted_at'];
}
