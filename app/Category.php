<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['subcategories'];

    protected $fillable = ['name','description'];

    public function materials()
    {
        return $this->hasMany('App\Material');
    }

    public function subcategories()
    {
        return $this->hasMany('App\Subcategory');
    }

    protected $dates = ['deleted_at'];
}
