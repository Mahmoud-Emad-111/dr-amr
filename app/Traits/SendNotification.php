<?php

namespace App\Traits;

use App\Models\User;
use App\Notifications\createItem;
use Illuminate\Support\Facades\Notification;

trait SendNotification
{
    /**
     * Save images
     */
    public function SendNotification($item,$message)
    {
        $user=User::all();
        Notification::send($user,new createItem($item,$message));

    }


}
