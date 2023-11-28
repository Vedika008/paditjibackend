<?php

namespace App\Http\Controllers;

use App\Models\newPooja;
use App\Models\PujaMaterials;
use Illuminate\Http\Request;
use App\Models\PanditjiRegistration;
use App\Models\PoojasThatPerformed;
use App\Models\yajman;
use App\Models\appointmentDetails;
use App\Models\city;
use App\Models\Community;
use App\Models\experience;
use App\Models\language;

use App\Models\state;
use App\Models\working_hr;

use Tymon\JWTAuth\Facades\JWTAuth;

use DB;


class yajmanController extends Controller
{

    public function __construct()
    {


    }

    // yajman creation
    public function yajmanCreation(Request $request)
    {
        try {
            $panditjiId = $request->id;
            // cheack if panditji exit then only yajman can create
            $panditji = new PanditjiRegistration();
            $panditjiExist = $panditji->checkPanditjiExistByItsId($panditjiId);
            $input = $request->all();
            if ($panditjiExist == false) {
                return response()->json(['status' => false, 'message' => 'Mobile Number not exist'], 400);
            } else {
                $yajman = new yajman();
                $yajmanExist = $yajman->cheackYajmanExist($panditjiId, $input['mobile_number']);


                // dd($yajmanExist);
                if ($yajmanExist) {
                    return response()->json(['status' => false, 'message' => 'Mobile Number Already exist'], 400);
                } else {
                    $yajman->yajman_name = $input['yajman_name'];
                    $yajman->mobile_number = $input['mobile_number'];
                    $yajman->state = $input['state'];
                    $yajman->city = $input['city'];
                    $yajman->address = $input['address'];
                    $yajman->date_of_birth = $input['date_of_birth'];
                    $yajman->created_by = $panditjiId;

                    // dd($yajman);
                    $save = $yajman->save();
                    if ($save) {
                        // id, pantiji_id, yajman_id, created_at, updated_at, created_by
                        $currentTimestamp = $this->generateTimestamp();
                        $yajmansUnderPanditji = $yajman->getYajmanUnderThePanditji($panditjiId);

                        DB::insert('insert into panditji_yajman_relation (pantiji_id, yajman_id, created_at,created_by) values(?,?,?,?)', [$panditjiId, $yajman->id, $currentTimestamp, $panditjiId]);

                        return response()->json(['status' => true, 'message' => 'Yajman register successfully', 'data' => $yajmansUnderPanditji], 200);
                    } else {
                        return response()->json(['status' => false, 'error' => 'Something went wrong', 'message' => "Patient Registration Failed"], 500);
                    }
                }
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    // get yajman details
    public function getYajmanDetails(Request $request)
    {
        /* all yajmans under pandit*/
        try {
            $panditjiId = $request->id;
            $yajman = new yajman();
            $yajmanDetails = $yajman->getYajmanUnderThePanditji($panditjiId);
            if ($yajmanDetails) {
                return response()->json(['status' => true, 'data' => $yajmanDetails], 200);
            }
            return response()->json(['status' => false, 'message' => 'Yajmans does not exist'], 400);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $th], 500);
        }
    }

    // get yajman details by id
    public function getYajmanDetailsByYajmanId(Request $request, $id)
    {
        try {
            $panditjiId = $request->id;
            $yajman = new yajman();
            $yajmanDetails = $yajman->getYajmanDetails($id);
            // dd($yajmanDetails);
            if ($yajmanDetails) {
                return response()->json(['status' => true, 'message' => 'Yajman details retrived successfully', 'data' => $yajmanDetails[0]], 200);
            }
            return response()->json(['status' => false, 'message' => 'Yajmans does not exist'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $th], 500);
        }
    }
}