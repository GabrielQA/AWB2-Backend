<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Illuminate\Support\Facades\Cache;
use PDO;
use Mail;

class verification extends Controller
{
    public function email(){
        session_start();
        //session()->regenerate();

        $value = Cache::get('key');
        //dd($value);
        $verification = "true";

    $conexion = new PDO("mysql:host=localhost;dbname=proyectoawb2","root","");
    $sql = "UPDATE users SET verification = true WHERE email = '$value';";
    $conexion->query($sql);


        //Cache::delete();
        return redirect("http://127.0.0.1:8000/email_verification");
 
     }

}
