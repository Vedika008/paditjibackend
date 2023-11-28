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
    
            // Save the updated Pooja
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
