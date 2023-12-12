<?php

namespace App\Http\Controllers;

use App\Models\appointmentDetails;
use App\Models\city;
use App\Models\Community;
use App\Models\experience;
use App\Models\language;
use App\Models\newPooja;
use App\Models\PanditjiRegistration;
use App\Models\PoojasThatPerformed;
use App\Models\state;
use App\Models\title;
use App\Models\working_hr;
use App\Models\yajman;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use DB;

class PantitjiController extends Controller
{
    /**
     * Operation Register
     *
     *
     * @return Http response
     */

    /**
     * @OA\Post(
     *      path="/api/v1/panditji/register",
     *      operationId="Register",
     *      tags={"Pandiji Registration"},
     *      summary="Register a panditji",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="personal_info", type="object",   
     *                      @OA\Property(property="title", type="string"),
     *                      @OA\Property(property="other_title",type="string"), 
     *                      @OA\Property(property="first_name", type="string" ,example ="vedika"),
     *                      @OA\Property(property="last_name", type="string",example ="jaware"),
     *                      @OA\Property(property="state", type="string",example="1"),
     *                      @OA\Property(property="district", type="string" ,example ="2"),
     *                      @OA\Property(property="address", type="string", example ="Mumbai")
     *                 ),
     *            @OA\Property(property="auth", type="object",   
     *                      @OA\Property(property="mobile_number", type="string" , example="7499670180"),
     *                 ),
     *            @OA\Property(property="community_and_language", type="object", 
     *                      @OA\Property(property="community",type="array",  @OA\Items(type="integer"), description="Array of community IDs"),
     *                      @OA\Property(property="othercommunity", type="string"),
     *                      @OA\Property(property="languages",type="array",  @OA\Items(type="integer"), description="Array of languages IDs"),
     *                      @OA\Property(property="otherlanguages", type="string")
     *             ),
     *            @OA\Property(property="other_info", type="object",   
     *                      @OA\Property(property="working_time", type="string"),
     *                      @OA\Property(property="experience", type="string"),
     *                      @OA\Property(property="otherlanguages", type="string"),
     *                      @OA\Property(property="working_in_temple", type="boolean"),
     *             ),
     *        ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="message",type="string", example="Panditji Registration Successful"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       ),
     *    @OA\Response(
     *          response=403, description="Internal Server Error",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="code",type="integer", example="403"),
     *             @OA\Property(property="message", type="string", example="Panfitji Registration Failed")
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
    // panditji registration
    public function PanditjiRegistration(Request $request)
    {
        try {
            $input = $request->all();
            $inputData = $input;

            $personalInfo = $inputData['personal_info'];
            $auth = $inputData['auth'];
            $communityAndLanguage = $inputData['community_and_language'];
            $otherInfo = $inputData['other_info'];

            $pr = new PanditjiRegistration();
            $panditExist = $pr->getPandithjiDetails($auth['mobile_number']);
            if ($panditExist == false) {
                $pr->title = $personalInfo['title'];
                $pr->other_title = $personalInfo['other_title'];
                $pr->first_name = $personalInfo['first_name'];
                $pr->last_name = $personalInfo['last_name'];
                $pr->address = $personalInfo['address'];
                $pr->state = $personalInfo['state'];
                $pr->district = $personalInfo['district'];

                $pr->mobile_number = $auth['mobile_number'];

                $pr->community = json_encode($communityAndLanguage['community']);
                $pr->other_community = json_encode($communityAndLanguage['othercommunity']);
                $pr->language = json_encode($communityAndLanguage['languages']);
                $pr->other_language = json_encode($communityAndLanguage['otherlanguages']);

                $pr->working_hr = $otherInfo['working_time'];
                $pr->experience = $otherInfo['experience'];
                // $pr->otherPooja = json_encode($otherInfo['other_pooja']);
                // $pr->poojasPerformed = json_encode($otherInfo['poojas_can']);
                $pr->working_in_temple = $otherInfo['working_in_temple'];
                // $string=implode(' ',$pr);
                // dd($string);
                // dd($pr);
                $save = $pr->save();

                if ($save) {
                    return response()->json(['status' => true, 'message' => "Pandit registration successful"], 200);
                } else {
                    return response()->json(['status' => false, 'error' => 'Something went wrong', 'message' => "Panditji Registration Failed"], 200);
                }
            } else {
                return response()->json(["status" => false, "message" => "Panditji is already exist"], 200);
            }
        } catch (\Throwable $th) {
            // dd($th);
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }


      /**
     * Operation getProfile
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/pandit/profile",
     *      operationId="getProfile",
     *      tags={"Pandiji Registration"},
     *      summary="Get panditji proile details",
    *      @OA\Parameter(
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
     *             @OA\Property(property="panditji_profile_details",type="object")
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

    public function getProfile(Request $request)
    {
        try {
            if (isset($request->id)) {
                $pandit = PanditjiRegistration::where('id', $request->id)->first();

                $panditji = new PanditjiRegistration();

                $dataa = $panditji->getProfileDetails($request->id);

                $com = new Community();
                $communityData = $com->getSubjectiveNamesForValues($dataa[0]->community);

                $createdPooja= newPooja::select('pooja_name')->where('created_by',$request->id)->get();

                // dd('other community',$dataa[0]->other_community);
                
                $lang = new language();
                $languageData = $lang->getSubjectiveNamesForValues($dataa[0]->language); 

                
                $poojaPerformed = new newPooja();
                $poojaperformedData = $poojaPerformed->getSubjectiveNamesForValues($dataa[0]->poojasPerformed); 
    
                $working_hr = new working_hr();
                $workingHrData = $working_hr->getSubjectiveNamesForValues($dataa[0]->working_hr);
    
                
                $exp = new experience();
                $experienceData = $exp->getSubjectiveNamesForValues($dataa[0]->experience);

                $title = new title();
                $titleData = $title->getSubjectiveNamesForValues($dataa[0]->title);

                $dataa[0]->title = $titleData;
                $dataa[0]->other_title = $dataa[0]['other_title'];
                $dataa[0]->community = $communityData;
                $dataa[0]->other_community = json_decode( $dataa[0]['other_community']);
                $dataa[0]->language = $languageData ;
                $dataa[0]->other_language =$dataa[0]['other_language'] ;
                $dataa[0]->poojasPerformed = $createdPooja;
                $dataa[0] ->working_hr = $workingHrData;
                $dataa[0] ->experience = $experienceData;
    
    
                $dataa[0]->otherPooja = json_decode($dataa[0]->otherPooja);

                return response()->json(['status' => true, 'data' => $dataa[0]], 200);
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }

    
    /**
     * Operation UpdateProfileDetails
     *
     *
     * @return Http response
     */

    /**
     * @OA\Put(
     *      path="/api/v1/pandit/profile",
     *      operationId="UpdateProfileDetails",
     *      tags={"Pandiji Registration"},
     *      summary="Update profile details of panditji",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="personal_info", type="object",   
     *                      @OA\Property(property="title", type="string"),
     *                      @OA\Property(property="other_title",type="string"), 
     *                      @OA\Property(property="first_name", type="string" ,example ="vedika"),
     *                      @OA\Property(property="last_name", type="string",example ="jaware"),
     *                      @OA\Property(property="state", type="string",example="1"),
     *                      @OA\Property(property="district", type="string" ,example ="2"),
     *                      @OA\Property(property="address", type="string", example ="Mumbai")
     *                 ),
     *            @OA\Property(property="auth", type="object",   
     *                      @OA\Property(property="mobile_number", type="string" , example="7499670180"),
     *                 ),
     *            @OA\Property(property="community_and_language", type="object", 
     *                      @OA\Property(property="community",type="array",  @OA\Items(type="integer"), description="Array of community IDs"),
     *                      @OA\Property(property="othercommunity", type="string"),
     *                      @OA\Property(property="languages",type="array",  @OA\Items(type="integer"), description="Array of languages IDs"),
     *                      @OA\Property(property="otherlanguages", type="string")
     *             ),
     *            @OA\Property(property="other_info", type="object",   
     *                      @OA\Property(property="working_time", type="string"),
     *                      @OA\Property(property="experience", type="string"),
     *                      @OA\Property(property="otherlanguages", type="string"),
     *                      @OA\Property(property="working_in_temple", type="boolean"),
     *             ),
     *        ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="message",type="string", example="Panditji Updated successfully"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       ),
     *    @OA\Response(
     *          response=403, description="Internal Server Error",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="false"),
     *             @OA\Property(property="code",type="integer", example="403"),
     *             @OA\Property(property="message", type="string", example="Panfitji updatation Failed")
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
   public function updateProfile(Request $request)
    {
        try {
            $panditjiId =$request->id;
            $panditji = PanditjiRegistration::find($panditjiId);
    
            if (!$panditji) {
                return response()->json(['status' => false, 'message' => 'Panditji not found'], 404);
            }
           
    
            $panditji->title = $request->input('personal_info.title');
            $panditji->first_name = $request->input('personal_info.first_name');
            $panditji->last_name = $request->input('personal_info.last_name');
            $panditji->state = $request->input('personal_info.state');
            $panditji->district = $request->input('personal_info.district');
            $panditji->address = $request->input('personal_info.address');
            $panditji->other_title = $request -> input('personal_info.other_title');

            // $panditji->mobile_number = $request->input('auth.mobile_number');
            //  dd($request->input('community_and_language')['othercommunity']);
            
            $panditji->community = $request->input('community_and_language.community');
            $panditji->other_community = $request->input('community_and_language')['othercommunity'];
            $panditji->language = $request->input('community_and_language.languages');
            $panditji->other_language  = $request->input('community_and_language')['otherlanguages'];

            
            $panditji->working_hr = $request->input('other_info.working_time');
            $panditji->experience = $request->input('other_info.experience');
            $panditji->working_in_temple = $request->input('other_info.working_in_temple');

            $save = $panditji->save();

            $dataa = $panditji->getProfileDetails($request->id);

            $com = new Community();
            $communityData = $com->getSubjectiveNamesForValues($dataa[0]->community);

            
            $lang = new language();
            $languageData = $lang->getSubjectiveNamesForValues($dataa[0]->language); 

            $poojaPerformed = new newPooja();
            $poojaperformedData = $poojaPerformed->getSubjectiveNamesForValues($dataa[0]->poojasPerformed); 

            $working_hr = new working_hr();
            $workingHrData = $working_hr->getSubjectiveNamesForValues($dataa[0]->working_hr);

            
            $exp = new experience();
            $experienceData = $exp->getSubjectiveNamesForValues($dataa[0]->experience);

            $title = new title();
            $titleData = $title->getSubjectiveNamesForValues($dataa[0]->title);

            $dataa[0]->title = $titleData;
            $dataa[0]->other_title = $dataa[0]['other_title'];


            $dataa[0]->title = $titleData;
            $dataa[0]->community = $communityData;
            $dataa[0]->other_community = json_decode($dataa[0]->other_community);
            $dataa[0]->language = $languageData ;
            $dataa[0]->other_language = json_decode($dataa[0]->other_language);
            $dataa[0]->poojasPerformed = $poojaperformedData;
            $dataa[0] ->working_hr = $workingHrData;
            $dataa[0] ->experience = $experienceData;



            $dataa[0]->otherPooja = json_decode($dataa[0]->otherPooja);

            if ($save) {
                return response()->json(['status' => true, 'message' => 'Profile updated successfully', 'data' => $dataa], 200);
            } else {
                return response()->json(['status' => false, 'error' => 'Something went wrong', 'message' => "Panditji updation Failed"], 200);
            }       
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
    
    
    /**
     * Operation getPanditjiRegistrationDetails
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/panditji/getPandithiRegistrationDetails/{mobileNumber}",
     *      operationId="getPanditjiRegistrationDetails",
     *      tags={"Pandiji Registration"},
     *      summary="Get panditji registration details",
     *      @OA\Parameter(
     *         name="mobileNumber",
     *         in="path",
     *         example=7499670180,
     *         required=true,
     *         description="mobileNumber",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="panditji_details",type="object")
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




    // retrive pandit registration details
    public function getPanditRegistrationDetails($mobileNo)
    {
        try {
            $panditji = new PanditjiRegistration();
            $data = $panditji->getPandithjiDetails($mobileNo);
            $structuredData = [
                "personal_info" => [
                    "title" => $data["title"],
                    "other_title" => $data["other_title"],
                    "first_name" => $data["first_name"],
                    "last_name" => $data["last_name"],
                    "state" => $data["state"],
                    "district" => $data["district"],
                    "address" => $data["address"]
                ],
                "auth" => [
                    "mobile_number" => $data["mobile_number"]
                ],
                "community_and_language" => [
                    "community" => [$data["community"]],
                    "othercommunity" => $data["other_community"],
                    "languages" => [$data["language"]],
                    "otherlanguages" => $data["other_language"]
                ],
                "other_info" => [
                    "working_time" => (int) $data["working_hr"],
                    "experience" => (int) $data["experience"],
                    "poojas_can" => [$data["poojasPerformed"]],
                    "other_pooja" => $data["otherPooja"],
                    "working_in_temple" => (bool) $data["working_in_temple"]
                ]
            ];


            if ($data == false) {
                return response()->json(['status' => false, 'message' => 'Panditji does not exist'], 400);
            } else {
                return response()->json(['status' => true, 'message' => 'Panditji data successfully retrived', 'data' => $structuredData], 200);
            }
        } catch (\Throwable $th) {


    
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }





    // community +lan+state+city apiss


    /**
     * Operation getUtilityDetails
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/panditji/utility",
     *      operationId="getUtilityDetails",
     *      tags={"Pandiji Registration"},
     *      summary="Get utlity details like community,languages,state,working hr ,and experience",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="utility_details",type="object")
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

    public function getUtilityDetails()
    {
        try {
            $panditjiPooja = new PoojasThatPerformed();
            $PoojaList = $panditjiPooja->getPoojalist();
            // dd($PoojaList);

            $panditjiCommunity = new Community();
            $CommunityList = $panditjiCommunity->getCommunityList();

            $panditjiLanguage = new language();
            $languageList = $panditjiLanguage->getLanguageList();

            $panditjiState = new state();
            $stateList = $panditjiState->getStateList();

            $panditjiWorkingHr = new working_hr();
            $workingHrList = $panditjiWorkingHr->getworkingHrList();

            $panditjiExperience = new experience();
            $experienceList = $panditjiExperience->getExperienceList();

            $title = new title();
            $titlelist = $title -> gettitleList();

            if ($PoojaList) {
                return response()->json(['status' => true, 'message' => 'Data retrived successfully', 'data' => ['community' => $CommunityList, 'language' => $languageList, 'state' => $stateList, 'workingHrList' => $workingHrList, 'experienceList' => $experienceList,'titleList' => $titlelist] ], 200);
            }
            return response()->json(['status' => false, 'message' => 'something went wrong'], 500);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    // extra apis


    /**
     * Operation poojaperformed
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/pandiji/poojasPerformed",
     *      operationId="poojaperformed",
     *      tags={"Pandiji Registration"},
     *      summary="Get pooja performed",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="pooja_details",type="object")
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
    public function getPoojasPerformedList()
    {
        try {
            $panditji = new PoojasThatPerformed();
            $PoojaList = $panditji->getPoojalist();

            if ($PoojaList == false) {
                return response()->json(['status' => false, 'message' => 'something went wrong'], 500);
            } else {
                return response()->json(['status' => true, 'message' => 'Pooja list retrived successfully', 'data' => $PoojaList], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }


    /**
     * Operation getCommunityList
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/pandiji/community",
     *      operationId="getCommunityList",
     *      tags={"Pandiji Registration"},
     *      summary="Get community list",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="community details",type="object")
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
    public function getCommunityList()
    {
        try {
            $panditji = new Community();
            $CommunityList = $panditji->getCommunityList();

            if ($CommunityList == false) {
                return response()->json(['status' => false, 'message' => 'somthing went wrong'], 500);
            } else {
                return response()->json(['status' => true, 'message' => 'Community list retrived successfully', 'data' => $CommunityList], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    /**
     * Operation getLanguageList
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/panditji/language",
     *      operationId="getLanguageList",
     *      tags={"Pandiji Registration"},
     *      summary="Get language list",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="landuage list",type="object")
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
    public function getLanguageList()
    {
        try {
            $panditji = new language();
            $languageList = $panditji->getLanguageList();
            // dd($languageList);
            if ($languageList == false) {
                return response()->json(['status' => false, 'message' => 'somthing went wrong'], 500);
            } else {
                return response()->json(['status' => true, 'message' => 'Language list retrived successfully', 'data' => $languageList], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    /**
     * Operation getState
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/panditji/states",
     *      operationId="getState",
     *      tags={"Pandiji Registration"},
     *      summary="Get state list",
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="state details",type="object")
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
    public function stateList()
    {
        try {
            $panditji = new state();
            $stateList = $panditji->getStateList();
            if ($stateList == false) {
                return response()->json(['status' => false, 'message' => 'somthing went wrong'], 500);
            } else {
                return response()->json(['status' => true, 'message' => 'state list retrived successfully', 'data' => $stateList], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    /**
     * Operation getCity
     *
     *
     *
     * @return Http response
     */

    /**
     * @OA\Get(
     *      path="/api/v1/panditji/cities/{stateId}",
     *      operationId="getCity",
     *      tags={"Pandiji Registration"},
     *      summary="Get city list",
     *      @OA\Parameter(
     *         name="stateId",
     *         in="path",
     *         example=21,
     *         required=true,
     *         description="stateId",
     *         @OA\Schema(type="integer")
     *     ),
     * 
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\Schema(type="application/pdf"),
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example="true"),
     *             @OA\Property(property="code",type="integer", example="200"),
     *             @OA\Property(property="city details",type="object")
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
    public function citiesList($stateId)
    {
        try {
            $panditji = new city();
            $citylist = $panditji->getcityListById($stateId);
            if ($citylist == false) {
                return response()->json(['status' => false, 'message' => 'somthing went wrong'], 500);
            } else {
                return response()->json(['status' => true, 'message' => 'city list retrived successfully', 'data' => $citylist], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function listOfYajmanUnderThePanditji(Request $request)
    {
        try {
            $panditjiId = $request->id;
            $yajman = new yajman();
            $yajmansUnderPanditji = $yajman->getYajmanUnderThePanditji($panditjiId);
            if ($yajmansUnderPanditji) {
                return response()->json(['status' => true, 'message' => 'Yajmans details retrived successfully', 'data' => $yajmansUnderPanditji], 200);
            }
            return response()->json(['status' => false, 'message' => 'No more yajmans under the panditji'], 400);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }
}
