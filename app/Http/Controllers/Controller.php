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
    function sendPush($title, $description, $uid = 0) {
        if ($uid > 0) {
            $content = ["en" => $description];
            $head = ["en" => $title];

            $daTags = [];
            $daTags = ["field" => "tag", "key" => "user_id", "relation" => "=", "value" => $uid];
            $fields = array(
                'app_id' => "af0f55a2-7271-4569-9557-5553382558fa",
                'included_segments' => array('All'),
                'filters' => [$daTags],
                'data' => array("foo" => "bar"),
                'contents' => $content,
                'headings' => $head,
            );


            $fields = json_encode($fields);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                'Authorization: Basic ZmFhMzMxZDgtNmU4Mi00YzI0LWJjYzAtNTA5YWNiNzMzOGM4'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        } else {
            return false;

        }
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
    public static function sendNotification($data) {
        $postdata = [
            "template"=>$data['template'],
            "envornment"=>$_ENV['ENVORNMENT'],
            "handle"=>$data['handle'],
            "appointment_id"=>$data['appointment_id']
        ];
        $fullApi = "http://kafka.docexa.com/send";


        if ($fullApi) {


            $url = $fullApi;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $http_result = $info ['http_code'];
         //   var_dump($output);die;
            curl_close($ch);
        }
    }
    public static function sendNotificationVideoCall($data) {
        $postdata = [
            "template"=>$data['template'],
            "envornment"=>$_ENV['ENVORNMENT'],
            "handle"=>$data['handle'],
            "usertype"=>$data['usertype'],
            'mobile_no' => $data['mobile_no'],
            "appointment_id"=>$data['appointment_id']
        ];
        $fullApi = "http://kafka.docexa.com/testfcm";


        if ($fullApi) {


            $url = $fullApi;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $http_result = $info ['http_code'];
         //   var_dump($output);die;
            curl_close($ch);
        }
    }
    public static function sendNotificationPatient($data) {
        $postdata = [
            "template"=>$data['template'],
            "envornment"=>$_ENV['ENVORNMENT'],
            "user_map_id"=>$data['user_map_id'],
            "appointment_id"=>$data['appointment_id']
        ];
        $fullApi = "http://kafka.docexa.com/sms/send";


        if ($fullApi) {


            $url = $fullApi;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $http_result = $info ['http_code'];
         //   var_dump($output);die;
            curl_close($ch);
        }
    }
    public static function sendNotificationDoc($data) {
        $postdata = [
            'template'=>$data['template'],
            'handle'=>$data['handle'],
            'appointment_id'=>$data['appointment_id'],
            'sender_email_id'=>$data['sender_email_id'],
            'sender_mobile_no'=>$data['sender_mobile_no']
        ];
        $fullApi = "http://kafka.docexa.com/sms/send";


        if ($fullApi) {


            $url = $fullApi;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $http_result = $info ['http_code'];
         //   var_dump($output);die;
            curl_close($ch);
        }
    }
    public static function sendNotificationPfizer($data) {
        $postdata = [
            "template"=>$data['template'],
            "envornment"=>$_ENV['ENVORNMENT'],
            "appointment_id"=>$data['appointment_id']
        ];
        $fullApi = "http://kafka.docexa.com/send/pfizer";


        if ($fullApi) {


            $url = $fullApi;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            $http_result = $info ['http_code'];
         //   var_dump($output);die;
            curl_close($ch);
        }
    }
    public static function urlshorten($inputurl) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://firebasedynamiclinks.googleapis.com/v1/shortLinks?key=AIzaSyDkTr_0ga5jgGHvEyatGtXWDLs0q76gJKk%0A',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "dynamicLinkInfo": {
            "domainUriPrefix": "https://docexa.page.link",
            "link": "'.$inputurl.'"
          },
            "suffix": {
             "option": "SHORT"
           }

        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $url = json_decode($response)->shortLink;
        return $url;

    }
    public static function sendwebhook($data) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://docexa.com/web/patient/webhookbooking',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$data,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
    public static function mediappapi($data) {
        $curl = curl_init();
        $send = array("data" => $data);
        Log::info([$send]);
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://product.solt.in/Mediapphealth/Vaccine/api.php',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_VERBOSE => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$data,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        Log::info([$info]);
        curl_close($curl);
    }

}