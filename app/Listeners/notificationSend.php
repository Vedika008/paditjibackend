<?php

namespace App\Listeners;

use App\Events\sendOtpNotification;
use App\Notifications\smsNotification;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Log;
use App\Notifications\Channels\ghupsupChannel;
use Illuminate\Contracts\Queue\ShouldQueue;

class notificationSend
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  sendOtpNotification  $event
     * @return void
     */
    public function handle(sendOtpNotification $event)
    {
        $panditjii = $event->panditjii;
        $otp = $event->otp;
        $mobile_number =$event->mobile_number;

        // Send the OTP notification via the Gupshup channel
       $panditjii->notify(new smsNotification($otp));
    }
}
