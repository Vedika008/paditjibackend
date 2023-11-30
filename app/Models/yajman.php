<?php

namespace App\Models;

use App\Models\city;

use Illuminate\Database\Eloquent\Model;
use Log;

class yajman extends Model
{
    protected $table = "yajman";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'yajman_name',
        'mobile_number',
        'state',
        'city',
        'address',
        'date_of_birth',
        'created_by',
        'created_at',
        'updated_at'
    ];

    public function cheackYajmanExist($panditiId, $mobile_number)
    {
        $yajmanExistOrnot = yajman::where('created_by', $panditiId)->where('mobile_number', $mobile_number)->exists();
        // dd($yajmanExistOrnot);

        if ($yajmanExistOrnot) {
            return true;
        }
        return false;
    }

    public function getYajmanDetails($yajmanId)
    {
        $yajmanDetails = yajman::where('id', $yajmanId)->get();
        // dd($yajmanDetails);
        if (count($yajmanDetails) > 0) {
            $arr = [];
            for ($i = 0; $i < count($yajmanDetails); $i++) {
                $element = $yajmanDetails[$i];
                $city = new city();
                $data = $city->getStateWithCity($element['state'], $element['city']);
                // dd($data);
                $element['state_id'] = $element['state'];
                $element['city_id'] = $element['city'];

                $element['state'] = $data['state'];
                $element['city'] = $data['city'];


                array_push($arr, $element);
            }
            return $arr;
        } else {
            return false;
        }

    }
    public function getYajmanUnderThePanditji($PanditId)
    {
        try {
            $yajmansUnderPanditji = yajman::where('created_by', $PanditId)->get();
            
            if (count($yajmansUnderPanditji) > 0) {
                $arr = [];
                for ($i = 0; $i < count($yajmansUnderPanditji); $i++) {
                    $element = $yajmansUnderPanditji[$i];
                    $city = new city();
                    $element['state_id'] = $element['state'];
                    $element['city_id'] = $element['city'];

                    $data = $city->getStateWithCity($element['state'], $element['city']);
                    $element['state'] = $data['state'];
                    $element['city'] = $data['city'];

                                

                    array_push($arr, $element);
                    // dd($arr);
                }
                return $arr;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            dd($th);
        }

    }
    function city(){
            return $this->belongsTo(city::class, 'city_id');
        }
}
