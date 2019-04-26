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
    Route::put('modusers', 'AuthController@modusers');
    Route::delete('deleteusers/{id}', 'AuthController@deleteusers');
    //CRUD VIDEOS
    Route::post('create_videos', 'AuthController@create_videos');
    Route::get('videos', 'AuthController@videos');
    Route::put('modvideos', 'AuthController@modvideos');
    Route::delete('deletevideos/{id}', 'AuthController@deletevideos');
    Route::post('seachvideo', 'AuthController@seachvideo');
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
