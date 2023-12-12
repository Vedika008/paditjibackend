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

    /**
     * Operation RegisterYajman
     *
     *
     * @return Http response
     */

    /**
     * @OA\Post(
     *      path="/api/v1/pandit/yajman/create",
     *      operationId="RegisterYajman",
     *      tags={"Yajman"},
     *      summary="Register a yajman",
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
     *                     @OA\Property(property="yajman_id", type="string", example="1"),
     *                      @OA\Property(property="yajman_name", type="string", example="mahesh"),
     *                      @OA\Property(property="mobile_number",type="string" ,example= "7499670180"),
     *                      @OA\Property(property="state", type="string" ,example ="21"),
     *                      @OA\Property(property="city", type="string",example ="384"),
     *                      @OA\Property(property="address", type="string",example="Gandhi square anjangaon bari"),
     *                      @OA\Property(property="date_of_birth", type="string" ,example ="2010/05/30"),
     *                      @OA\Property(property="created_by", type="string",example="1")
     *        ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="message",type="string", example="Yajman Registration Successful"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       ),
     *    @OA\Response(
     *          response=403, description="Internal Server Error",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="code",type="integer", example="403"),
     *             @OA\Property(property="message", type="string", example="Yajman Registration Failed")
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
                if ($yajmanExist) {
                    return response()->json(['status' => false, 'message' => 'Mobile Number Already exist'], 400);
                } else {
                    $yajman->yajman_name = $input['yajman_name'];
                    $yajman->mobile_number = $input['mobile_number'];
                    $yajman->state = $input['state'];
                    $yajman->city = $input['city'];
                    $yajman->address = $input['address'];
                    $yajman->date_of_birth = null;
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
                        return response()->json(['status' => false, 'error' => 'Something went wrong', 'message' => "Yajman Registration Failed"], 500);
                    }
                }
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    // get yajman details

    /**
     * Operation getYajmanDetails
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/pandit/getYajmans",
     *      operationId="getYajmanDetails",
     *      tags={"Yajman"},
     *      summary="Get yajman registration details",
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
     *             @OA\Property(property="Yajman_details",type="object")
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
    public function getYajmanDetails(Request $request)
    {
        /* all yajmans under pandit*/
        try {
            $panditjiId = $request->id;
            $yajman = new yajman();
            // $y =$yajman->find(1)->get();
            //     dd($y);
            $yajmanDetails = $yajman->getYajmanUnderThePanditji($panditjiId);
            // dd($yajmanDetails);
            if ($yajmanDetails) {
                return response()->json(['status' => true, 'data' => $yajmanDetails], 200);
            }
            return response()->json(['status' => false, 'message' => 'Yajmans does not exist'], 200);
        } catch (\Throwable $th) {
            // dd($th);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $th], 500);
        }
    }

    // get yajman details by id
    /**
     * Operation getYajmanDetailsById
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/pandit/view/{id}",
     *      operationId="getYajmanDetailsById",
     *      tags={"Yajman"},
     *      summary="Get yajman detail ",
     *       @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="id",
     *          @OA\Schema(type="string")
     *      ),
     *   @OA\Parameter(
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
     *             @OA\Property(property="Yajman_detail",type="object")
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


    public function getYajmanDetailsByYajmanId(Request $request, $id)
    {
        try {
            $panditjiId = $request->id;
            $yajman = new yajman();
            $yajmanDetails = $yajman->getYajmanDetails($panditjiId, $id);
            // dd($yajmanDetails);
            if ($yajmanDetails) {
                return response()->json(['status' => true, 'message' => 'Yajman details retrived successfully', 'data' => $yajmanDetails[0]], 200);
            }
            return response()->json(['status' => false, 'message' => 'Yajmans does not exist'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error', 'error' => $th], 500);
        }
    }

   /**
     * Operation deleteYajmanById
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Delete(
     *      path="/api/v1/pandit/deleteYajman/{id}",
     *      operationId="deleteYajmanById",
     *      tags={"Yajman"},
     *      summary="delete yajman detail ",
     *       @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="id",
     *          @OA\Schema(type="string")
     *      ),
     *   @OA\Parameter(
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
     *             @OA\Property(property="Yajman deleted successfully")
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


    public function deleteYajman(Request $request, $id)
    {
        try {

            $panditjiId = $request->id;
            $apt = new yajman();
            $YajmanExistOrNot = $apt->IsYajman($panditjiId, $id);


            if (!$YajmanExistOrNot) {
                return response()->json(['status' => false, 'message' => 'Resource not found'], 200);
            }

            $YajmanExistOrNot->relation()->delete();
            $YajmanExistOrNot->delete();

            return response()->json(['status' => true, 'message' => 'Resource deleted successfully'], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }
      /**
     * Operation updateYajman
     *
     *
     * @return Http response
     */

    /**
     * @OA\Put(
     *      path="/api/v1/pandit/updateYajman/{id}",
     *      operationId="updateYajman",
     *      tags={"Yajman"},
     *      summary="Update a yajman",
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer Token",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="id",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *                     @OA\Property(property="yajman_id", type="string", example="1"),
     *                      @OA\Property(property="yajman_name", type="string", example="mahesh"),
     *                      @OA\Property(property="mobile_number",type="string" ,example= "7499670180"),
     *                      @OA\Property(property="state", type="string" ,example ="21"),
     *                      @OA\Property(property="city", type="string",example ="384"),
     *                      @OA\Property(property="address", type="string",example="Gandhi square anjangaon bari"),
     *                      @OA\Property(property="date_of_birth", type="string" ,example ="2010/05/30"),
     *                      @OA\Property(property="created_by", type="string",example="1")
     *        ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="message",type="string", example="Yajman updated Successfully"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       ),
     *    @OA\Response(
     *          response=403, description="Internal Server Error",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="code",type="integer", example="403"),
     *             @OA\Property(property="message", type="string", example="Yajman updatation Failed")
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

     public function updateYajmanDetails(Request $request,$id){
        try {
            $input = $request ->all();
            $panditjiId = $request->id;
            $yajman = new yajman();
            $YajmanExistOrNot = $yajman->IsYajman($panditjiId, $id);

            if(!$YajmanExistOrNot){
                return response()->json(['status' =>false , 'message' => 'yajman does not exist'],200);
            }else{
                $yajman->yajman_name = $input['yajman_name'];
                $yajman->mobile_number = $input['mobile_number'];
                $yajman->state = $input['state'];
                $yajman->city = $input['city'];
                $yajman->address = $input['address'];
                $yajman->date_of_birth = null;
                $yajman->created_by = $panditjiId;
                $save = $yajman->save();
                if ($save) {
                    return response()->json(['status' => true, 'message' => 'Yajman updated successfully', 'data' =>$YajmanExistOrNot], 200);
                } else {
                    return response()->json(['status' => false, 'error' => 'Something went wrong', 'message' => "Yajman updatation Failed"], 500);
                }
            }

        } catch (\Throwable $th) {
            return response()->json(['status' => false,"message" => 'Internal server error'], 200);

        }
     }
}
