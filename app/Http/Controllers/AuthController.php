<?php

namespace App\Http\Controllers;


use Authy\AuthyApi;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;
use Session;
use Illuminate\Support\Facades\Cache;
use PDO;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
        $this->middleware('guest');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function verificationRequestValidator(array $data)
    {
        return Validator::make($data, [
            'country_code' => 'required|string|max:3',
            'phone_number' => 'required|string|max:10',
            'via' => 'required|string|max:4',
        ]);
    }

    /**
     * Get a validator for an code verification request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function verificationCodeValidator(array $data)
    {
        return Validator::make($data, [
            'country_code' => 'required|string|max:3',
            'phone_number' => 'required|string|max:10',
            'token' => 'required|string|max:4'
        ]);
    }
    public function login(Request $request, AuthyApi $authyApi
    )
    {
        $Email=$request->email;
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or password does\'t exist'], 401);
        }
        //Condicion de si verificacion del correo
        $conexion = new PDO("mysql:host=localhost;dbname=proyectoawb2","root","");
        $sql = "SELECT verification FROM users WHERE email = '$Email';";
        $info2 = $conexion->prepare($sql); 
        $info2->execute();
        $verification = $info2->fetch();
        $Validation=$verification[0];
        if($Validation == 0){
            return response()->json(['error' => 'This Email need verification'], 401);
        }else{
            //$conexion = new PDO("mysql:host=localhost;dbname=proyectoawb2","root","");
            $sql = "SELECT telephone FROM users WHERE email = '$Email';";
            $info2 = $conexion->prepare($sql); 
            $info2->execute();
            $telephone = $info2->fetch(); 
            //Cache::put('kayn', $telephone[0]);
            $country_code='506';
            $phone_number=$telephone[0]; 
            $via="sms";
    
           //Aqui enviamos el sms
            $response = $authyApi->phoneVerificationStart($phone_number, $country_code, $via);
            //return redirect("http://127.0.0.1:8000/phone");
    




            //AQUI SE LOGUEA Y MANDA EL TOKEN
         
    
            return $this->respondWithToken($token);



        }       

        
    }

    public function signup(SignUpRequest $request)
    {

        $Email=$request->email;
        session_start();
        session()->regenerate();
        Cache::put('key', $Email);

        session(['123' => $Email]);

        User::create($request->all());
     
        
        
       // $Age=$request->birthdate;
       
        //dd($Age);
        //Email envio
        Mail::send('email', [], function($message){
            $value = session('123');
            $message->from('Administracion@gmail.com', 'Gabriel QA');
            $message->to($value,'GabrielQuesada')->subject('Verfique su cuenta');
        });
        return response()->json(['primary' => 'Check your email'], 401);


       //Ejemplo de enviar a otra ruta por el controlador
       //return redirect("http://127.0.0.1:8000/email")->compact('Email');

       
    }

    public function validation(Request $request){
        
        dd($request);
       /* $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or password does\'t exist'], 401);
        }

        return $this->respondWithToken($token);*/
       //return redirect("http://127.0.0.1:8000/");

    }
    public function code(){

    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function email(){

        Mail::send('email', [], function($message){
            $message->from('Administracion@gmail.com', 'Gabriel QA');
            $message->to('@gmail.com','GabrielQuesada')->subject('Verfique su cuenta');
        });
    
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()->name
        ]);
    }

    
}