<?php

namespace LaraH5P\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class H5PNotification extends Notification implements ShouldQueue
{
    public function handle($event)
    {
        // Handle the notification logic
    }

    public function failed($event, $exception)
    {
        // Handle failure scenarios
    }
}
