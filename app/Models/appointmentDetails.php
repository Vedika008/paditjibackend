<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointmentDetails extends Model
{
    use HasFactory;
    protected $table = 'appointment_creation';
    protected $primaryKey = 'id';

    protected $fillable = [
        'yajman_name',
        'mobile_number',
        'state',
        'city',
        'address',
        'date',
        'pooja',
        'other_pooja',
        'pooja_material',
        'created_at',
        'updated_at',
        'created_by',
        'time'
    ];

    public function getAppointmentDetails($panditId)
    {
        try {
            $yajmanDetails = appointmentDetails::where('created_by', $panditId)
            ->orderBy('created_at', 'asc')
            ->get();
            // dd($yajmanDetails);

            if (count($yajmanDetails) > 0) {
                $arr = [];
                for ($i = 0; $i < count($yajmanDetails); $i++) {
                    $element = $yajmanDetails[$i];
                    if ($element['pooja'] != 0) {
                        $pooja = newPooja::select('pooja_name')->where('id', $element['pooja'])->get();
                        $element['pooja_name'] = $pooja[0]['pooja_name'];
                    }

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

    public function getAppointmentDetailsByID($appointmentId)
    {

        try {
            $yajmanDetail = appointmentDetails::where('id', $appointmentId)
            ->orderBy('created_at', 'asc')
            ->get();

            // if(count($yajmanDetail)>0){
            //     return $yajmanDetail;
            // }else{
            //     return false;
            // }

         
            if (count($yajmanDetail) > 0) {
                $arr = [];
                for ($i = 0; $i < count($yajmanDetail); $i++) {
                    $element = $yajmanDetail[$i];
                    if ($element['pooja'] != 0) {
                        $pooja = newPooja::select('pooja_name')->where('id', $element['pooja'])->get();
                        $element['pooja_name'] = $pooja[0]['pooja_name'];
                    }

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
}
