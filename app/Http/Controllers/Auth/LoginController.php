<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\SyClass\System\Interfaces\InterfaceResponse;
use App\SyClass\Helpers\Response;

class LoginController extends Controller
                            implements InterfaceResponse
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'app/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //$this->middleware('guest')->except('logout');
    }
	
	/**
     * verifica si esta logueado
     * 
     * @return type
     * @throws \Exception
     */
    public function main(){
        try{
            // verificamos si existe session de usuario
            if(\Auth::guest()){
                return \View::make("login");
            }else{
                
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
	
    /**
     * intentamos hacer login de un usuario
     * 
     * @return type
     * @throws \Exception
     */
    public function authLogin(){
        try{
            // intentamos iniciar sersion
            $rsp = (new \App\SyClass\System\Authenticate())
                    ->verifyLoginUser(\Request::get("username"), \Request::get("password"));
            
            if($rsp){
                return \Redirect::to('app/home/main/read');
            }else{
                return \Redirect::to('login')
                        ->with('error', 'error')
                        ->with('message', 'Datos Invalidos!')
                        ->withInput();
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    
    /**
     * 
     * 
     * @return type
     * @throws \Exception
     */
    public function logOutSystem(){
        try{
           (new \App\SyClass\System\Authenticate())->closeSession($this);
            
            return \Redirect::to('/login')
                    ->with('error', 'success')
                    ->with('message', 'Tu sesión ha sido cerrada.');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /*********************************++
     * interface response json
     * 
     * @param type $jsonResponse
     * @return type
     * @throws \Exception
     ************************************/
    public function callBackResponse($jsonResponse = null,$error=false,$type="success") {
        try{
            return (new Response())->responseRequest($jsonResponse,$error,$type);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
