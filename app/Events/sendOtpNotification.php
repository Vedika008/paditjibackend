<?php

namespace App\Events;

use App\Notifications\Channels\ghupsupChannel;
use Illuminate\Queue\SerializesModels;
use App\Events\Dispatchable;

class sendOtpNotification extends Event
{
    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $otp;
    public $mobile_number;

    public $panditjii;



    public function __construct($panditjii,$otp, $mobile_number)
    {   $this->panditjii = $panditjii;
        $this->otp = $otp;
        $this->mobile_number = $mobile_number;
    }
    public function toNotification($notifiable)
    {
        return [ghupsupChannel::class];

    }

}

