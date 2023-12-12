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
        'other_title',
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
        $panditjiRegistrationDetails = PanditjiRegistration::where('mobile_number', $mobile_number)
        ->orderBy('created_at', 'asc')
        ->first();
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
   
    public function getProfileDetails($id)
    {
        $panditjiRegistrationDetails = PanditjiRegistration::where('id', $id)
        ->orderBy('created_at', 'asc')
        ->get();
        // dd(count($panditjiRegistrationDetails));
        if (count($panditjiRegistrationDetails) > 0) {
            $arr = [];
            for ($i = 0; $i < count($panditjiRegistrationDetails); $i++) {
                $element = $panditjiRegistrationDetails[$i];
                $city = new city();
                // dd($element);
                $data = $city->getStateWithCityy($element['state'], $element['district']);
                $element['state_id'] = $element['state'];
                $element['city_id'] = $element['district'];

                $element['state'] = $data['state'];
                $element['district'] = $data['city'];
             
               array_push($arr, $element);
            }
            // dd($arr);
            return $arr;
        } else {
            return false;
        }

    }


}
