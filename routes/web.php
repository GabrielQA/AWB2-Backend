<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email_verification', function () {
    return view('email_verification');
});
    //Route::get('email', 'AuthController@email');

    Route::get('sms', 'PhoneVerificationController@codigo');

    Route::get('email', 'verification@email');
    Route::get('phone', 'PhoneVerificationController@startVerification');
   //Route::post('verify', 'PhoneVerificationController@codigo');
   Route::get('verify', 'AuthController@validation');


/*Route::get('email', function () {

    Mail::send('email', [], function($message){
        $message->from('Administracion@gmail.com', 'Gabriel QA');
        $message->to('gabrielqqquesada@gmail.com','GabrielQuesada')->subject('Verfique su cuenta');
    });

    //return 'Su cuenta esta siendo procesada';
});*/
//Route::get('/email_verification','AuthController@email_verification'); 
//Route::get('email', 'AuthController@email_verification');
/*
Route::get('email_verification', function () {
    //return view('email_verification');


    //Conexion
    
        /*$conexion = new PDO("mysql:host=localhost;dbname=secondproject","root","");

        $sql = "SELECT categoria FROM categorias WHERE categoria = '$idcate';";
        $info2 = $conexion->prepare($sql); 
        $info2->execute();
        $stock = $info2->fetch();
        $categoria = new Categoria();
                $categoria->categoria = $idcate;
                $categoria->descri = $request->input('des');
                $categoria->save();*/
/*
    return redirect("http://127.0.0.1:4200/email");


});*/

Route::get('userskids', 'AuthController@userskids');
