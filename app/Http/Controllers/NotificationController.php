<?php

namespace App\Http\Controllers;

use App\Notification;
use App\NotificationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        Carbon::setLocale('es');
        $notificationUsers = NotificationUser::with('notification')
            ->where("user_id",Auth::user()->id)
            /*->where(function($query)
            {
                $query->where("read",0)
                    ->where("read",0);
            })*/
            ->where(function($query)
            {
                $query->where("date_delete",null)
                    ->orWhere("date_delete", '>', Carbon::now());
            })->get();
        //dd($notificationUsers);

        $notifications = [];

        foreach ( $notificationUsers as $notificationUser )
        {
            $id_notification = $notificationUser->notification->id;
            $id_notification_user = $notificationUser->id;
            $message = $notificationUser->notification->content;
            $url_go = $notificationUser->notification->url_go;
            $read = $notificationUser->read;
            $time = $notificationUser->created_at->diffForHumans();

            array_push($notifications, [
                'id_notification' => $id_notification,
                'id_notification_user' => $id_notification_user,
                'message' => $message,
                'url_go' => $url_go,
                'read' => $read,
                'time' => ucfirst($time)
            ]);

        }

        return response()->json(['notifications' => $notifications], 200);
        //dump($notifications);
    }

    public function readNotification( $idNotificationUser )
    {
        DB::beginTransaction();
        try {
            $notificationUser = NotificationUser::find($idNotificationUser);
            $notificationUser->read = 1;
            $notificationUser->date_read = Carbon::now();
            $notificationUser->date_delete = Carbon::now()->addDays(2);
            $notificationUser->save();
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Notificación leída. Se quitará del listado en 2 días'], 200);


    }
}
