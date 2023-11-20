<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Log;
use Tymon\JWTAuth\Contracts\JWTSubject;

class PanditjiRegistration extends Model implements JWTSubject
{
    use Notifiable;

    protected $table = "Registration";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'first_name',
        'last_name',
        'address',
        'state',
        'district',
        'mobile__number',
        'community',
        'other_community',
        'language',
        'other_language',
        'working_hr',
        'experience',
        'poojasPerformed',
        'otherPooja',
        'working_in_temple',
        'created_at',
        'updated_at'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getPandithjiDetails($mobile_number)
    {
        $panditjiRegistrationDetails = PanditjiRegistration::where('mobile_number', $mobile_number)->first();
        if ($panditjiRegistrationDetails !== null) {
            return $panditjiRegistrationDetails;
        }else{
            return false;
        }

    }
    public function checkPanditjiExistByItsId($panditId){
        $panditExistOrNot = PanditjiRegistration::find($panditId);

        if ($panditExistOrNot) {
            return true;
        }

        return false;
    }


}
