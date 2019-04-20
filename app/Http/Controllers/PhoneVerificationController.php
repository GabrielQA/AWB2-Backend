<?php

namespace App\Http\Controllers;

use \Exception;
use Session;
use App\Http\Controllers\Controller;
use Authy\AuthyApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use PDO;
use App\User;
use App\Http\Requests\SignUpRequest;


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
