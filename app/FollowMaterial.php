<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowMaterial extends Model
{
    protected $fillable = [
        'material_id',
        'user_id',
        'state'
    ];

    public function material()
    {
        return $this->belongsTo('App\Material');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
