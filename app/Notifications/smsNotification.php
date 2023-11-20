<?php
namespace App\Notifications;

use App\Events\sendOtpNotification;
use APP\Notifications;
use App\Notifications\Channels\ghupsupChannel;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class smsNotification extends Notification
{
    use Notifiable;
    protected $otp;

    // public function via($notifiable)
    // {
    //     return ['sms'];
    // }

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return [ghupsupChannel::class];
    }

    public function toGupshup($notifiable)
    {
        // Define the OTP message to send via Gupshup
        // dd($notifiable->otp);
        return 'Dear User Your OTP for Docexa is ' . $this->otp;
    }

}
