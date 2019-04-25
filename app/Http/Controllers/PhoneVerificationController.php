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


class PhoneVerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Phone Verification  Controller
    |--------------------------------------------------------------------------
    |
    | Uses Authy to verify a users phone via voice or sms.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming verification request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
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

    /**
     * Request phone verification via PhoneVerificationService.
     *
     * @param  array  $data
     * @return Illuminate\Support\Facades\Response;
     */
    public function phone(){
       
    }
    public function viewUsers(){
        $Father = Cache::get('father');  
        //La #1      
        //Normalito
        /*$conexion = new PDO("mysql:host=localhost;dbname=pawb2","root","");
        $sql = "SELECT name,username,birthdate FROM userskids WHERE id_father = '$Father';";
        $info2 = $conexion->prepare($sql); 
        $info2->execute();
        $telephone = $info2->fetch();
        dd($telephone);*/
        //return response()->json($telephone);


        //La #2
        // JWT
        dd(response()->json(['details'=> UsersKids::all()], 200));
         return response()->json(['details'=> UsersKids::all()], 200);
       
       /* OR
        $tasks = Tasks::all();
        return $tasks;*/
       
        //La #3
        //Basic Auth
        //return response()->json(['details'=> UsersKids::all()], 200);

        /*La #4
        Normal
        $student = UsersKids::find($id);
        return response()->json($student);*/
    }
    protected function startVerification(
        Request $request,
        AuthyApi $authyApi
    ) {
            session_start();


        /*$data = $request->all();
        $validator = $this->verificationRequestValidator($data);
        extract($data);*/
        $value = Cache::get('kayn');
        $country_code='506';
        $phone_number=$value; 
        $via="sms";
    
           //Aqui enviamos el sms
            $response = $authyApi->phoneVerificationStart($phone_number, $country_code, $via);
 

            //return redirect("http://localhost:4200/");
            
    }
    /**
     * Request phone verification via PhoneVerificationService.
     *
     * @param  array  $data
     * @return Illuminate\Support\Facades\Response;
     */
    protected function verifyCode(
        Request $request,
        AuthyApi $authyApi
    ) {
        $data = $request->all();
        $validator = $this->verificationCodeValidator($data);
        extract($data);

        if ($validator->passes()) {
            try {
                $result = $authyApi->phoneVerificationCheck($phone_number, $country_code, $token);
                return response()->json($result, 200);
            } catch (Exception $e) {
                $response=[];
                $response['exception'] = get_class($e);
                $response['message'] = $e->getMessage();
                $response['trace'] = $e->getTrace();
                return response()->json($response, 403);
            }
        }
        return response()->json(['errors'=>$validator->errors()], 403);
    }
}
