<?php 
Route::group([
    'middleware' => 'api',
], function () {
    Route::post('validation', 'AuthController@validation');
    Route::get('/', function () {
        return view('welcome');
    });
    Route::post('login', 'AuthController@login');
    Route::post('validation', 'AuthController@login');
    //CRUD USUARIOS
    Route::post('create_users', 'AuthController@create_users');
    Route::get('userskids', 'AuthController@userskids');
    Route::post('modusers', 'AuthController@modusers');
    Route::post('deleteusers', 'AuthController@deleteusers');
    //CRUD VIDEOS
    Route::get('create_videos', 'AuthController@create_videos');
    Route::post('viewvideos', 'AuthController@viewvideos');
    Route::post('modvideos', 'AuthController@modvideos');
    Route::post('deletevideos', 'AuthController@deletevideos');
    //Registrar Usuarios corrientes
    Route::post('signup', 'AuthController@signup');
    //Route::get('email', 'AuthController@email');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('sendPasswordResetLink', 'ResetPasswordController@sendEmail');
    Route::post('resetPassword', 'ChangePasswordController@process');
    /*Route::prefix('verify/')->group(function () {
        Route::post('start', 'PhoneVerificationController@startVerification');
        Route::post('verify', 'PhoneVerificationController@verifyCode');
    });*/
});
//Route::get('phone', 'PhoneVerificationController@startVerification');
