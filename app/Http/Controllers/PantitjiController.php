<?php

namespace App\Http\Controllers;

use App\Models\appointmentDetails;
use App\Models\city;
use App\Models\Community;
use App\Models\experience;
use App\Models\language;
use App\Models\PanditjiRegistration;
use App\Models\PoojasThatPerformed;
use App\Models\state;
use App\Models\working_hr;
use App\Models\yajman;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

use DB;

class PantitjiController extends Controller
{
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
                $pr->otherPooja = json_encode($otherInfo['other_pooja']);
                $pr->poojasPerformed = json_encode($otherInfo['poojas_can']);
                $pr->working_in_temple = $otherInfo['working_in_temple'];
                // $string=implode(' ',$pr);
                // dd($string);
                // dd($pr);
                $save = $pr->save();

                if ($save) {
                    return response()->json(['status' => true, 'message' => "Pandit registered successfully"], 200);
                } else {
                    return response()->json(['status' => false, 'error' => 'Something went wrong', 'message' => "Panditji Registration Failed"], 200);
                }
            } else {
                return response()->json(["status" => false, "message" => "Panditji is already exist"], 200);
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        }
    }
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

            if ($PoojaList) {
                return response()->json(['status' => true, 'message' => 'Data retrived successfully', 'data' => ['pooja' => $PoojaList, 'community' => $CommunityList, 'language' => $languageList, 'state' => $stateList, 'workingHrList' => $workingHrList, 'experienceList' => $experienceList]], 200);
            }
            return response()->json(['status' => false, 'message' => 'something went wrong'], 500);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    // extra apis
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
