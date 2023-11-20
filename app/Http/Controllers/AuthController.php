<?php

namespace App\Http\Controllers;

use App\Events\sendOtpNotification;
use App\Models\otpVerification;
use App\Models\PanditjiRegistration;
use App\Models\User;
use App\Notifications\SmsNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Cache;
use Tymon\JWTAuth\Facades\JWTAuth;






class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        // $this->middleware('auth:api', ['expect' => ['login']]);
    }
    public function generateAccesstoken($panditjiId)
    {
        $accessToken = encrypt($panditjiId);
        return response()->json(['access_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $input = $request->all();
            $mobileNumber = $request->input('mobile_number');
            $enteredOTP = $request->input('otp');

            $cachedOtp = Cache::get('otp' . $mobileNumber);

            if ($cachedOtp ==  $enteredOTP) {
                // Check if user exists in the database
                $panditji = PanditjiRegistration :: where('mobile_number' ,$mobileNumber)->first();

                if ($panditji) {
                    $token = JWTAuth::fromUser($panditji);

                    // Respond with the token and other details upon successful authentication
                    return response()->json([
                        'user' => $panditji,
                        'access_token' => $token,
                        'message' => 'Login successful',
                    ]);



                    // $token = JWTAuth::attempt($request->only('mobile_number', 'otp'));

                    // dd($token);
                    // if (!$token) {
                    //     return response()->json(['error' => 'Unauthorized'], 401);
                    // }else{
                    //     return response()->json([
                    //         'panditji' => $panditji,
                    //         'access_token' => $token,
                    //         'message' => 'Login successful',
                    //     ]);
                    // }



                } else {
                   return response() ->json(['status' => true , 'message'=>'invalid credentials'],400);
                }
            } else {
                return response()->json(['status' =>false, 'message' => 'OTP verification failed'], 401);
            }

    }

    public function otpGenerate($mobilenumber)
    {
        try {
            $otp = random_int(100000, 999999);
            // find the panditji whetre uu want to send sms
            $panditjii = PanditjiRegistration::where('mobile_number', $mobilenumber)->first();

            if ($panditjii) {
                event(new sendOtpNotification($panditjii, $otp, $mobilenumber));
                $msg = "Dear User Your OTP for Docexa is " . $otp;
                $data = $this->sendSms($mobilenumber, $msg);
                if ($data) {
                    Cache::put('otp' . $mobilenumber, $otp, $this->generateTimestamp()->addMinutes(2));
                    return response()->json(['status' => true, 'message' => 'Otp sent successfully'], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Internal server error'], 400);
                }

            } else {
                return response()->json(['status' => false, 'message' => 'Panditji does not exist'], 400);

            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }
    public function otpVerify(Request $request)
    {
        try {
            $input = $request->all();
            $cachedOtp = Cache::get('otp' . $input['mobileNumber']);
            var_dump(Cache::get('otp' . $input['mobileNumber']));
            var_dump($input['otp']);
            var_dump($cachedOtp == $input['otp']);
            if ($cachedOtp == $input['otp']) {
                Cache::forget('otp' . $input['mobileNumber']);
                return response()->json(['status' => true, 'message' => 'OTP verified'], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Otp is incorrect'], 200);
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

    public function sendOtp(Request $request)
    {
        // Retrieve the necessary variables from the request
        $API_USER_ID = env('API_USER_ID');
        $API_USER_PASSWORD = env('API_USER_PASSWORD');
        $MESSAGE_MASK = env('MESSAGE_MASK');

        $OTP = $request->input('OTP');
        $mobile = $request->input('mobile');

        // Construct the API request URL
        $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=" . $mobile;
        $url .= "&msg=Dear%20User%20Your%20OTP%20for%20Docexa%20is%20" . $OTP;
        $url .= "&msg_type=TEXT&userid=" . $API_USER_ID . "&auth_scheme=plain&password=" . $API_USER_PASSWORD;
        $url .= "&v=1.1&format=text&mask=" . $MESSAGE_MASK;

        //    dd($url);

        // Use the HTTP facade to send the request
        $response = Http::get($url);

        // dd($response);

        // Check the response to determine if the SMS was sent successfully
        if ($response->successful()) {
            return response('OTP sent successfully', 200);
        } else {
            // Handle the error, you can log the response or return an appropriate error response
            return response('Failed to send OTP', 500);
        }
    }




    public function verifyOtp($mobileNumber, $enteredOTP)
    {
        try {
            $cachedOtp = Cache::get('otp' . $mobileNumber);

            if ($cachedOtp == $enteredOTP) {
                Cache::forget('otp' . $mobileNumber);
               return true;
            } else {
               return false;
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
        }
    }

}






// login previous logic
// try {

//     $input = $request->all();
//     $user = new otpVerification();
//     $user->mobile_no = $input['mobile_no'];

//     //  cheack mobile number is exist or not
//     $panditji = new PanditjiRegistration();
//     $cheackPanditJiExist = $panditji->getPandithjiDetails($input['mobile_no']);

//     if ($cheackPanditJiExist == false) {
//         return response()->json(['status' => false, 'message' => 'Invalid credentials'], 400);
//     } else {
//         $checkIfOTPSend = $this->otpGenerate($input['mobile_no']);

//         if ($checkIfOTPSend == false) {
//             return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
//         } else {
//             return response()->json(['status' => true, 'message' => 'OTP sent successfully on ' . $input['mobile_no']], 200);
//         }
//     }
// } catch (\Throwable $th) {
//     return response()->json(['status' => false, 'message' => 'Internal server error'], 500);
// }

