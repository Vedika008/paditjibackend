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

    $router->get('/panditji/utility', 'PantitjiController@getUtilityDetails');

    $router->get('/pandiji/poojasPerformed', 'PantitjiController@getPoojasPerformedList');

    $router->get('/pandiji/community', 'PantitjiController@getCommunityList');

    $router->get('/panditji/language', 'PantitjiController@getLanguageList');

    $router->get('/panditji/states', 'PantitjiController@stateList');
    $router->get('/panditji/cities/{stateId}', 'PantitjiController@citiesList');

    $router->post('/panditji/generateOtp/{mobilenumber}', 'PantitjiController@generateOTP');

    // create yajman
    // $router->post('/panditji/{panditjiId}/create/yajman', 'PantitjiController@yajmanCreation');


    //create pooja appointemnt
    $router->post('/panditji/{panditjiId}/createAppoinment/{yajmanId}', 'PantitjiController@createAppointment');
    $router->get('/panditji/{panditjiId}/getAppointmentDetails', 'PantitjiController@getAppointmentDetails');

    // register
    $router->get('/register/checkAndGenerateOtp/{mobilenumber}', 'AuthController@otpforRegister');
    $router->post('/verifyOtp', 'AuthController@otpVerify');

    // login
    $router->get('/login/checkAndGenerateOtp/{mobilenumber}', 'AuthController@otpGenerateForLogin');
    $router->post('/login/verify', 'AuthController@LoginVerify');
});

$router->group(['prefix' => 'api/v1/pandit/', 'middleware' => 'auth'], function () use ($router) {   
    /* yajaman apis  */
    $router->post('yajman/create', 'PantitjiController@yajmanCreation');
    $router->get('getYajmans','PantitjiController@getYajmanDetails');
    $router->get('getYajman/{id}', 'PantitjiController@getYajmanDetailsByYajmanId');
});