<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    protected $fillable = [
        'notification_id',
        'role_id',
        'user_id',
        'read',
        'date_read',
        'date_delete'
    ];

    public function notification()
    {
        return $this->belongsTo('App\Notification');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
