<?php

namespace App\Http\Controllers;

use App\Models\newPooja;
use App\Models\PujaMaterials;
use Illuminate\Http\Request;
use App\Models\PanditjiRegistration;
use App\Models\PoojasThatPerformed;
use App\Models\yajman;
use App\Models\appointmentDetails;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\yajmanController;



class AppointmentController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Operation CreateAppointment
     *
     *
     * @return Http response
     */

    /**
     * @OA\Post(
     *      path="/api/v1/pandit/appointment/create",
     *      operationId="CreateAppointment",
     *      tags={"Appointment"},
     *      summary="make a appointment for the yajman",
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer Token",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *             @OA\Property(property="yajmanId", type="string", example="1"),
     *             @OA\Property(property="yajman_name", type="string", example="Vedika"),
     *             @OA\Property(property="mobile_number", type="string", example="7499670180"),
     *             @OA\Property(property="state", type="string", example="21"),
     *             @OA\Property(property="city", type="string", example="328"),
     *             @OA\Property(property="address", type="string", example="Gandhi square,anjangaon bari"),
     *             @OA\Property(property="date", type="string", example="20/10/2023"),
     *             @OA\Property(property="pooja", type="string", example="Vidhi"),
     *             @OA\Property(property="other_pooja", type="string"),
     *             @OA\Property(property="pooja_material", type="string")          
     *        ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="message",type="string", example="Appointment created Successfully"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       ),
     *    @OA\Response(
     *          response=403, description="Internal Server Error",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="code",type="integer", example="403"),
     *             @OA\Property(property="message", type="string", example="Appointmnet creation Failed")
     *          )
     *       ),
     *     @OA\Response(
     *          response=500, description="Internal Server Error",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="code",type="integer", example="500"),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *          )
     *       )
     *  )
     */


    // create appointment for the yajman
    public function createAppointment(Request $request)
    {
        try {
            $panditjiId = $request->id;
            $input = $request->all();

            $yajman = new yajman();
            $yajmanExistby = $yajman->cheackYajmanExist($panditjiId, $input['mobile_number']);
            // dd($input['yajmanId']);

            if ($input['yajmanId'] == null || $yajmanExistby == false) {
                $yajman = new yajmanController();
                $res = $yajman->yajmanCreation($request);
                // if ($yajmanCreationResponse->successful()) {
                //     $createdYajmanData = $yajmanCreationResponse->json();
                // } else {
                //     return response()->json(['status' => false, 'message' => 'Yajman creation failed'], 500);
                // }
            }
            $yajman = new yajman();
            $yajmanExist = $yajman->cheackYajmanExist($panditjiId, $input['mobile_number']);
            if ($yajmanExist == false) {
                return response()->json(['status' => false, 'message' => 'yajman does not exist'], 400);
            } else {
                $apnt = new appointmentDetails();
                $apnt->yajman_name = $input['yajman_name'];
                $apnt->yajman_mobile_no = $input['mobile_number'];
                $apnt->state = $input['state'];
                $apnt->city = $input['city'];
                $apnt->address = $input['address'];
                $apnt->date = $input['date'];
                $apnt->pooja = $input['pooja'];
                $apnt->other_pooja = $input['other_pooja'];
                $apnt->pooja_material = $input['pooja_material'];
                $apnt->time = $input['time'];
                $apnt->created_by = $panditjiId;
                $save = $apnt->save();
                // dd($save);

                if ($save) {
                    return response()->json(['status' => true, 'message' => 'Appointment created successfully'], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
                }
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    /**
     * Operation getAppointmentDetails
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/pandit/appointment/view",
     *      operationId="getAppointmentDetails",
     *      tags={"Appointment"},
     *      summary="get all the appointments",
     *  @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer Token",
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="Appointment_details",type="object")
     *          )
     *       ),
     *     @OA\Response(
     *          response=500, description="Internal Server Error",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *          )
     *       )
     *  )
     */



    // get all appointment that are under pandit 
    public function getAppointmentDetails(Request $request)
    {
        try {
            $panditjiId = $request->id;
            $apt = new appointmentDetails();
            $appointmentDetails = $apt->getAppointmentDetails($panditjiId);

            if ($appointmentDetails == false) {
                return response()->json(['status' => false, 'message' => 'No more appointments under this panditji', 'data' => []], 400);
            } else {
                return response()->json(['status' => true, 'message' => 'Appointment details retrived successfully', 'data' => $appointmentDetails], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }


    /**
     * Operation getAppointmentById
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/pandit/appointment/view/{id}",
     *      operationId="getAppointmentById",
     *      tags={"Appointment"},
     *      summary="get a appointment by Id",
     *    @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer Token",
     *          @OA\Schema(type="string")
     *      ),
     *    @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="id",
     *          @OA\Schema(type="string")
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="Appointment_detail",type="object")
     *          )
     *       ),
     *     @OA\Response(
     *          response=500, description="Internal Server Error",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *          )
     *       )
     *  )
     */



    // get all appointment that are under pandit 


    // get particular appointment 
    public function getAppointmentById(Request $request, $id)
    {
        try {
            // $panditjiId = $request->id;
            $apt = new appointmentDetails();
            $appointmentDetail = $apt->getAppointmentDetailsByID($id);
            // print_r($appointmentDetail);
            if ($appointmentDetail == false) {
                return response()->json(['status' => false, 'message' => 'No more appointment for this id', 'data' => []], 200);
            } else {
                return response()->json(['status' => true, 'message' => 'Appointment detail retrived successfully', 'data' => $appointmentDetail[0]], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

}