<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
use Laravel\Lumen\Routing\Controller as BaseController;
use DB;
use Log;

/**
 * Class Controller
 * @package App\Http\Controllers
 * @OA\OpenApi(
 *     @OA\Info(
 *         description="Panditji Micro Service API",
 *         version="1.0.0",
 *         title="Panditji Micro Service API Development",
 *         termsOfService = "https://panditjisamagri.com/",
 *         @OA\Contact(email="satish.soni@globalspace.in")
 *     ),
 *     @OA\Server( 
 *          url= "https://panditjisamagri.com/"
 *     ),
 * )
 */
class Controller extends BaseController {


	 public function __construct()
	 {
		 error_log(print_r($_REQUEST,true));
		Log::info([$_REQUEST]);
    }

    public static function generateTimestamp()
    {
        return Carbon::now();
    }

    public static function sendSms($num, $msg) {
        $fullApi = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to={num}&msg={msg}&msg_type=TEXT&userid=2000153330&auth_scheme=plain&password=nbm0jALBl&v=1.1&format=text&mask=GSTDOC";
        $msg = urlencode($msg);

        if ($fullApi) {
            $api = str_replace(['{msg}', '{num}'], [$msg, $num], $fullApi);

            $url = $api;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $http_result = $info ['http_code'];
            curl_close($ch);
            return true;
        }
        return false;
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

