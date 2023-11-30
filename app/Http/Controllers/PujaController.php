<?php

namespace App\Http\Controllers;

use App\Models\newPooja;
use App\Models\PujaMaterials;
use Illuminate\Http\Request;
use App\Models\PanditjiRegistration;
use App\Models\PoojasThatPerformed;


class PujaController extends Controller
{

    public function __construct()
    {
    }

    // puja Creation

 /**
     * Operation PujaCreation
     *
     *
     * @return Http response
     */

    /**
     * @OA\Post(
     *      path="/api/v1/pandit/poojaMaterial/create",
     *      operationId="PujaCreation",
     *      tags={"New Pooja"},
     *      summary="Cration of new pooja",
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
     *                     @OA\Property(property="pooja_name", type="string"),
     *                     @OA\Property(property="pooja_material",type="array",  @OA\Items(type="string"), description="Array of pooja material"),
     *                      @OA\Property(property="created_by",type="string" )
     *        ),
     *     ),
     * 
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="message",type="string", example="Pooja added successfully"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       ),
     *    @OA\Response(
     *          response=403, description="Internal Server Error",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="code",type="integer", example="403"),
     *             @OA\Property(property="message", type="string", example="Pooja Creation Failed")
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
    public function addPuja(Request $request)
    {
        try {
            $panditjiId = $request->id;

            $input = $request->all();

            $panditji = new PanditjiRegistration();
            $panditjiExist = $panditji->checkPanditjiExistByItsId($panditjiId);

            if ($panditjiExist == false) {
                return response()->json(['status' => false, 'message' => 'Panditji does not exist'], 400);
            } else {
                //craete pooja
                $pooja = new newPooja();
                $pooja->pooja_name = $input['pooja_name'];
                $pooja->pooja_material = json_encode($input['pooja_material']);          
                $pooja->created_by = $panditjiId;
                $save = $pooja->save();
                if ($save) {
                    return response()->json(['status' => true, 'message' => "Pooja added successfullly"], 200);
                } else {
                    return response()->json(['status' => false, 'error' => 'Something went wrong', "message" => 'Creation of puja failed'], 200);
                }
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal Server Error', 'error' => $th], 500);
        }
    }


     /**
     * Operation getPujaThatIsCreated
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/pandit/poojaMaterial/view",
     *      operationId="getPujaThatIsCreated",
     *      tags={"New Pooja"},
     *      summary="Get pujas which is craeted by the panditji",
     *  @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="id",
     *          @OA\Schema(type="string")
     *      ),
     *   *  @OA\Parameter(
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
     *             @OA\Property(property="poojaCreated",type="object")
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

    // get all puja that createded by panditji
    public function getAllPujaThatCreated()
    {
        try {
            $pooja = new newPooja();
            $poojaList = $pooja->getPoojalist();

            if ($poojaList == false) {
                return response()->json(['status' => false, 'message' => 'No puja created', 'data' => []], 200);
            }
            return response()->json(['status' => true, 'message' => 'Pooja list retrived successfully', 'data' => $poojaList], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    // get all puja materials that require for the pujaaaa
    public function getAllPoojaMaterials(Request $request)
    {
        try {
            $pooja = new PujaMaterials();
            $poojaMaterialList = $pooja->getpoojaMaterialList();
            // dd($poojaMaterialList);

            if ($poojaMaterialList == false) {
                return response()->json(['status' => false, 'message' => 'No puja created', 'data' => []], 500);
            } else {
                return response()->json(['status' => true, 'message' => 'Pooja Material list retrived successfully', 'data' => $poojaMaterialList], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

  /**
     * Operation getPujaById
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/pandit/poojaMaterial/view/{id}",
     *      operationId="getPujaById",
     *      tags={"New Pooja"},
     *      summary="Get puja which is craeted by the panditji",
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
     *             @OA\Property(property="poojaCreated",type="object")
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

    // get all puja that createded by panditji
    
    public function getPujaById (Request $request , $id){
        try {
            $pooja = new newPooja();
            $poojaMaterial = $pooja->getPoojaById($id);
                // dd($poojaMaterial);

            if ($poojaMaterial == false) {
                return response()->json(['status' => false, 'message' => 'No puja created for this id', 'data' => []], 500);
            } else {
                return response()->json(['status' => true, 'message' => 'Pooja Material retrived successfully', 'data' => $poojaMaterial[0]], 200);
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    // update
    public function updatePooja(Request $request, $id)
    {
        try {
            $pooja = newPooja::find($id);
    
            if (!$pooja) {
                return response()->json(['status' => false, 'message' => 'Pooja not found'], 404);
            }
    
            $input = $request->all();
   
            $pooja->pooja_name = $input['pooja_name'] ?? $pooja->pooja_name;
            $pooja->pooja_material = json_encode($input['pooja_material'] ?? $pooja->pooja_material);
    
            $save = $pooja->save();
    
            if ($save) {
                return response()->json(['status' => true, 'message' => "Pooja updated successfully"], 200);
            } else {
                return response()->json(['status' => false, 'error' => 'Something went wrong', "message" => 'Update of pooja failed'], 500);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal Server Error', 'error' => $th], 500);
        }
    }
    
}
