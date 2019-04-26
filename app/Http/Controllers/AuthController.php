<?php

namespace App\Http\Controllers;


use Authy\AuthyApi;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\SignUpKids;
use App\Http\Requests\SignUpVideos;
use App\User;
use App\UsersKids;
use App\Videos;
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
        
        $this->middleware('auth:api', ['except' => ['login', 'signup','create_users','create_videos','userskids',
        'videos','modvideos',
        'deletevideos','modusers','deleteusers','seachvideo']]);
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
        //Aqui  valido el id que ingreso cuando creo o un video o un usuario
        $Email=$request->email;
        $conexion = new PDO("mysql:host=localhost;dbname=pawb2","root","");
        $sql = "SELECT id FROM users WHERE email = '$Email';";
        $info2 = $conexion->prepare($sql); 
        $info2->execute();
        $Father = $info2->fetch();
        Cache::put('father', $Father[0]);


        //Verifica si existen los datos ingresados
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or password does\'t exist'], 401);
        }

        //Condicion de si verificacion del correo
        $conexion = new PDO("mysql:host=localhost;dbname=pawb2","root","");
        $sql = "SELECT verification FROM users WHERE email = '$Email';";
        $info2 = $conexion->prepare($sql); 
        $info2->execute();
        $verification = $info2->fetch();
        $Validation=$verification[0];
        if($Validation == 0){
            return response()->json(['error' => 'This Email need verification'], 401);
        }else{
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


            //AQUI SE LOGUEA Y MANDA LA INFO
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
    //CRUD USERS KIDS
    public function create_users(SignUpKids $request){

        $conexion = new PDO("mysql:host=localhost;dbname=pawb2","root","");
        $Father = Cache::get('father');        
        //Verificar mas adelante
        /*$UsersKids = UsersKids::create($request->all());
        return $this->SignUpKids($request);*/
        $sql = "INSERT INTO userskids (name,id_father,username,password,birthdate) VALUES ('$request->name',$Father,'$request->username', '$request->password',$request->birthdate)";
        $info2 = $conexion->prepare($sql); 
        $info2->execute();
        $verification = $info2->fetch();
        
    }
    //Function view UsersKids 
    public function userskids(){
        
         return response()->json(UsersKids::all(), 200);
       
    }
    //Function Modify Userskids
    public function modusers(Request $request){
    
        $UsersKids = userskids::find($request->id)->update($request->all());
        return response()->json($UsersKids, 200);
    }
    //Function Delete UsersKids
    public function deleteusers($id){
        $UsersKids = userskids::find($id)->delete();
        return response()->json(null, 204);
    }

    //CRUD VIDEOS
    //Function create videos
    public function create_videos(SignUpVideos $request){

        $Father = Cache::get('father');
        $request['id_father'] = $Father;
        $UsersKids = Videos::create($request->all());
    }
    //Fuction view videos
    public function videos(){
        return response()->json(Videos::all(), 200);
    }
    //Function search videos
    public function seachvideo(Request $request){
        $data=Videos::Where('name','LIKE',"%$request->name%")->get();
        return response()->json($data, 200);
    }
    //Function Modify Videos
    public function modvideos(Request $request){
        $UsersKids = userskids::find($request->id)->update($request->all());
        return response()->json($UsersKids, 200);
    }
    //Function delete videos
    public function deletevideos($id){
        $video = videos::find($id)->delete();
        return response()->json(null, 204);
    }
    

   //Others validations
    public function validation(Request $request){
        
        dd($request->all());
       /* $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or password does\'t exist'], 401);
        }

        return $this->respondWithToken($token);*/
       //return redirect("http://127.0.0.1:8000/");

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