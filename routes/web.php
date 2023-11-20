<?php
use App\Http\Controllers\Controller;
use App\Http\Controllers\PatientController;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    $timestamp = new Controller();
    return response()->json(['status' => true, 'timestamp' => $timestamp->generateTimestamp()], 200);
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    // pantiji registration
    $router->post('/panditji/register', 'PantitjiController@PanditjiRegistration');
    $router->get('/panditji/getPandithiRegistrationDetails/{mobileNo}', 'PantitjiController@getPanditRegistrationDetails');

    $router->get('/pandiji/poojasPerformed', 'PantitjiController@getPoojasPerformedList');

    $router->get('/pandiji/community', 'PantitjiController@getCommunityList');

    $router->get('/panditji/language', 'PantitjiController@getLanguageList');

    $router->get('/panditji/states', 'PantitjiController@stateList');
    $router->get('/panditji/cities/{stateId}', 'PantitjiController@citiesList');

    $router->post('/panditji/generateOtp/{mobilenumber}', 'PantitjiController@generateOTP');

    // create yajman
    $router->post('/panditji/{panditjiId}/create/yajman', 'PantitjiController@yajmanCreation');
    $router->get('/panditji/{panditjiId}/getYajmanDetails/{yajmanId}', 'PantitjiController@getYajmanDetails');

    //create pooja appointemnt
    $router->post('/panditji/{panditjiId}/createAppoinment/{yajmanId}', 'PantitjiController@createAppointment');
    $router->get('/panditji/{panditjiId}/getAppointmentDetails', 'PantitjiController@getAppointmentDetails');

    // Login api using mobile numebewr
    $router->post('/panditji/login', 'AuthController@Login');

    // $router->post('generate/accesstoken', 'AuthController@generateAccesstoken');

    $router->get('/generateOtp/{mobilenumber}', 'AuthController@otpGenerate');
    $router->post('/verifyOtp', 'AuthController@otpVerify');
    $router->post('/send-otp', 'AuthController@sendOtp');










});
