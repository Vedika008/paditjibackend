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

    // register
    $router->get('/register/checkAndGenerateOtp/{mobilenumber}', 'AuthController@otpforRegister');
    $router->post('/verifyOtp', 'AuthController@otpVerify');

    // login
    $router->get('/login/checkAndGenerateOtp/{mobilenumber}', 'AuthController@otpGenerateForLogin');
    $router->post('/login/verify', 'AuthController@LoginVerify');
});

$router->group(['prefix' => 'api/v1/pandit/', 'middleware' => 'auth'], function () use ($router) {
    /* yajaman apis  */
    $router->get('profile', 'PantitjiController@getProfile');
    $router->put('profile', 'PantitjiController@updateProfile');


    $router->post('yajman/create', 'yajmanController@yajmanCreation');
    $router->get('getYajmans', 'yajmanController@getYajmanDetails');
    $router->get('getYajman/{id}', 'yajmanController@getYajmanDetailsByYajmanId');
    $router->delete('deleteYajman/{id}', 'yajmanController@deleteYajman');
    $router->put('updateYajman/{id}', 'yajmanController@updateYajmanDetails');


});

$router->group(['prefix' => 'api/v1/pandit/poojaMaterial/', 'middleware' => 'auth'], function () use ($router) {
    $router->post('create', 'PujaController@addPuja');

    $router->get('view', 'PujaController@getAllPujaThatCreated');
    $router->get('view/{id}', 'PujaController@getPujaById');
    $router->put('update/{id}', 'PujaController@updatePooja');
    $router->delete('deleteCreatedPuja/{id}', 'PujaController@deletePuja');
    $router->get('getPujaMaterial', 'PujaController@getAllPoojaMaterials');
    $router->put('updatePuja/{id}', 'PujaController@updatePujaDetails');

});

$router->group(['prefix' => 'api/v1/pandit/appointment', 'middleware' => 'auth'], function () use ($router) {
    /*Appointment apis for yajman */
    $router->post('create', 'AppointmentController@createAppointment');
    $router->get('view', 'AppointmentController@getAppointmentDetails');
    $router->get('view/{id}', 'AppointmentController@getAppointmentById');
    $router->delete('delete/{id}', 'AppointmentController@deleteAppointment');
    $router->put('updateAppointment/{id}', 'AppointmentController@updateAppointment');


});
