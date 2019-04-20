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
