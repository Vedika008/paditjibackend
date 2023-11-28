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

                // id, pooja_name, pooja_material, created_at, updated_at, created_by, materialid, materialName, materialQuantity

                $pooja->pooja_name = $input['pooja_name'];
                $pooja->pooja_material = json_encode($input['pooja_material']); // Serialize the nested object

                // $pooja->pooja_material = json_encode($input['pooja_material']); 
                // $personalInfo = $input['pooja_material'];
                // $pooja-> materialid = $personalInfo['materialid'];
                // $pooja->materialName = $personalInfo['materialName'];
                // $pooja->materialQuantity = $personalInfo['materialQuantity'];
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
}
