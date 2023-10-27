<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryEquipment extends Model
{
    use SoftDeletes;

    protected $table = 'category_equipments';

    protected $fillable = [
        'description',
        'image'
    ];

    public function default_equipments()
    {
        return $this->hasMany('App\DefaultEquipment');
    }

    protected $dates = ['deleted_at'];
}
