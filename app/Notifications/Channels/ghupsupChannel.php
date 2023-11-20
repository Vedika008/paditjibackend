<?php
namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ghupsupChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toGupshup($notifiable);
        // dd($message);

        // $API_USER_ID = env('API_USER_ID');
        // $API_USER_PASSWORD = env('API_USER_PASSWORD');
        // $MESSAGE_MASK = env('MESSAGE_MASK');
        // $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=" . $mobile;
        // $url .= "&msg=Dear%20User%20Your%20OTP%20for%20Docexa%20is%20" . $OTP;
        // $url .= "&msg_type=TEXT&userid=" . $API_USER_ID . "&auth_scheme=plain&password=" . $API_USER_PASSWORD;
        // $url .= "&v=1.1&format=text&mask=" . $MESSAGE_MASK;
        // $response = Http::get($url);

        // if ($response->successful()) {
        //     return response('OTP sent successfully', 200);
        // } else {
        //     return response('Failed to send OTP', 500);
        // }

        $response = Http::get('http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage', [
            'form_params' => [
                'send_to' => $notifiable->mobile_number,
                'msg' => $message,
                'msg_type' => 'TEXT',
                'userid' => env('API_USER_ID'),
                'auth_scheme' => 'plain',
                'password' => env('API_USER_PASSWORD'),
                'v' => '1.1',
                'format' => 'text',
            ],
        ]);
        if ($response->successful()) {
            return true;
        } else {
            return false;
        }
    }
}
