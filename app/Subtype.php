<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtype extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','description', 'material_type_id'
    ];

    // TODO: Las relaciones
    public function materials()
    {
        return $this->hasMany('App\Material');
    }

    public function materialType()
    {
        return $this->belongsTo('App\MaterialType');
    }

    protected $dates = ['deleted_at'];
}
