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
            $appointmentdetails = appointmentDetails::where('created_by', $panditId)
            ->orderBy('created_at', 'desc') 
            ->get();
            if (count($appointmentdetails) > 0) {
                $arr = [];
                for ($i = 0; $i < count($appointmentdetails); $i++) {
                    $element = $appointmentdetails[$i];
                    $poojaExist = newPooja::find($element['pooja']);

                    if ($poojaExist || $element['pooja']==0) {
                        if ($element['pooja']) {
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
                    }
                }
                return $arr;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function getAppointmentDetailsByID($panditjiId, $appointmentId)
    {

        try {
            $appointmentdetail = appointmentDetails::where('created_by', $panditjiId)
                ->where('id', $appointmentId)
                ->orderBy('created_at', 'desc')
                ->get();


            if (count($appointmentdetail) > 0) {
                $arr = [];
                for ($i = 0; $i < count($appointmentdetail); $i++) {
                    $element = $appointmentdetail[$i];
                    $poojaExist = newPooja::find($element['pooja']);

                    if ($poojaExist || $element['pooja']==0) {
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
                    }
                }
                return $arr;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function IsAppointment($panditjiId, $id)
    {
        $checkAppointment = appointmentDetails::where('created_by', $panditjiId)->where('id', $id)->first();
        return $checkAppointment;

    }
}
