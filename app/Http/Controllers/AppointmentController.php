<?php

namespace App\Http\Controllers;

use App\Models\newPooja;
use App\Models\PujaMaterials;
use Illuminate\Http\Request;
use App\Models\PanditjiRegistration;
use App\Models\PoojasThatPerformed;
use App\Models\yajman;
use App\Models\appointmentDetails;



class AppointmentController extends Controller
{

    public function __construct()
    {
    }

    // create appointment for the yajman
    public function createAppointment($yajmanId, Request $request)
    {
        try {
            $panditjiId = $request->id;
            // firstly if checak yajman exist
            $yajman = new yajman();
            $yajmanExist = $yajman->getYajmanDetails($yajmanId);
            if ($yajmanExist == false) {
                return response()->json(['status' => false, 'message' => 'yajman does not exist'], 400);
            } else {
                $input = $request->all();
                $apnt = new appointmentDetails();
                $apnt->yajman_name = $input['yajman_name'];
                $apnt->yajman_mobile_no = $input['yajman_mobile_no'];
                $apnt->state = $input['state'];
                $apnt->city = $input['city'];
                $apnt->address = $input['address'];
                $apnt->date = $input['date'];
                $apnt->pooja = $input['pooja'];
                $apnt->other_pooja = $input['other_pooja'];
                $apnt->pooja_material = $input['pooja_material'];
                $apnt->created_by = $panditjiId;
                $save = $apnt->save();
                // dd($apnt->save());
                if ($save) {
                    return response()->json(['status' => true, 'message' => 'Appointment created successfully'], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    // get all appointment that are under pandit 
    public function getAppointmentDetails(Request $request)
    {
        try {
            $panditjiId = $request->id;
            $apt = new appointmentDetails();
            $appointmentDetails = $apt->getAppointmentDetails($panditjiId);

            if ($appointmentDetails == false) {
                return response()->json(['status' => false, 'message' => 'No more appointments under this panditji' , 'data' =>[]], 200);
            } else {
                return response()->json(['status' => true, 'message' => 'Appointment details retrived successfully', 'data' => $appointmentDetails], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    // get particular appointment 
    public function getAppointmentById(Request $request){
        try {
            $panditjiId = $request->id;
            $apt = new appointmentDetails();
            $appointmentDetail = $apt->getAppointmentDetailsByID($panditjiId);
            if ($appointmentDetail == false) {
                return response()->json(['status' => false, 'message' => 'No more appointment for this id' ,'data'=> []],200);
            } else {
                return response()->json(['status' => true, 'message' => 'Appointment detail retrived successfully', 'data' => $appointmentDetail[0]], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

}