<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
ini_set('memory_limit', '-1');
error_log(print_r($_REQUEST,true));
use Laravel\Lumen\Routing\Controller as BaseController;
use DB;
use Log;
/**
 * Class Controller
 * @package App\Http\Controllers
 * @OA\OpenApi(
 *     @OA\Info(
 *         description="Docexa Doctor Micro Service API",
 *         version="1.0.0",
 *         title="Docexa Doctor Micro Service API staging",
 *         termsOfService = "https://docexa.com/",
 *         @OA\Contact(email="satish.soni@globalspace.in")
 *     ),
 *     @OA\Server(
 *          url= "http://staging.docexa.com/api/v3"
 *     ),
 *     @OA\Server(
 *          url= "https://staging.docexa.com/api/v3"
 *     ),
 *     @OA\Server(
 *          url= "http://staging.docexa.com/api/v2"
 *     ),
 *     @OA\Server(
 *          url= "https://staging.docexa.com/api/v2"
 *     ),
 *     @OA\Tag(
 *        name="Doctors",
 *        description="Everything about your Doctors",
 * ),
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
    public function use(){
        return true;
    }

}