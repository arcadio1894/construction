<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryInvoice extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','description'];

    public function entries()
    {
        return $this->hasMany('App\Entry');
    }

    protected $dates = ['deleted_at'];
}
