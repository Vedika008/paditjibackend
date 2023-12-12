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
        $yajmanExistOrnot = yajman::where('created_by', $panditiId)->where('mobile_number', $mobile_number)
        ->orderBy('created_at', 'asc') 
        ->exists();
        // dd($yajmanExistOrnot);

        if ($yajmanExistOrnot) {
            return true;
        }
        return false;
    }

    public function getYajmanDetails($panditjiId,$yajmanId)
    {
        $yajmanDetails = yajman::where('created_by',$panditjiId)
        ->where('id', $yajmanId)
        ->orderBy('created_at', 'asc') 
        ->get();
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
            $yajmansUnderPanditji = yajman::where('created_by', $PanditId)
            ->orderBy('created_at', 'asc') 
            ->get();
            
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
        
    public function IsYajman($panditjiId, $id){
        $IsYajman = yajman::where('created_by', $panditjiId)->where('id', $id)->first();
        return $IsYajman; 
    
    }
    public function relation() {
        return $this->hasMany(panditjiYajmanRelation::class);
    }

      
}
