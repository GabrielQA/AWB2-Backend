<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;
use Session;

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
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or password does\'t exist'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function signup(SignUpRequest $request)
    {
        $Email=$request->email;
        /*session_start();
        session()->regenerate();
       

        session(['123' => $Email]);
       
        User::create($request->all());*/
     
        
        
        $Age=$request->birthdate;
       
        dd($Age);
        //Email envio
        /*Mail::send('email', [], function($message){
            $value = session('123');
           
            $message->from('Administracion@gmail.com', 'Gabriel QA');
            $message->to($value,'GabrielQuesada')->subject('Verfique su cuenta');
        });*/


       //Ejemplo de enviar a otra ruta por el controlador
       //return redirect("http://127.0.0.1:8000/email")->compact('Email');

       
    }

    public function email_verification(){

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
            $message->to('gabrielqqquesada@gmail.com','GabrielQuesada')->subject('Verfique su cuenta');
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