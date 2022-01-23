<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'content',
        'reason_for_creation',
        'user_id',
        'url_go'
    ];

    public function notification_users()
    {
        return $this->hasMany('App\NotificationUser');
    }

}
