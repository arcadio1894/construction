<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialType extends Model
{
	use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['subtypes'];

	protected $fillable = [
    	'name','description', 'subcategory_id'
    ];

    // TODO: Las relaciones
    public function materials()
    {
        return $this->hasMany('App\Material');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Subcategory');
    }

    public function subtypes()
    {
        return $this->hasMany('App\Subtype');
    }

    protected $dates = ['deleted_at'];
}
