<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['materialTypes'];

    protected $fillable = ['name','description', 'category_id'];

    public function materials()
    {
        return $this->hasMany('App\Material');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function materialTypes()
    {
        return $this->hasMany('App\MaterialType');
    }

    protected $dates = ['deleted_at'];
}
