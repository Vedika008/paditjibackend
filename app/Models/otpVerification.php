<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class otpVerification extends Model
{
    protected $table = "otp_verification";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'mobile_no', 'otp', 'otp_created_at', 'otp_expires_at'
    ];

    public function CheackOTP($panditjiId, $OtpOfuser){
       $otpStored = OTPVerification::where('user_id', $panditjiId)->value('otp');

       if($otpStored == $OtpOfuser){
        return true;
       }else{
        return false;
       }

    }
}
