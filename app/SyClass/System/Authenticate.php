<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Authenticate
 *
 * @author johnm
 */
namespace App\SyClass\System;
use \App\SyClass\DB\FacCRUD;
class Authenticate {
    //put your code here
    
    /**
     * verifica si el usuario y pass es valido
     * 
     * @param type $user
     * @param type $pass
     * @throws \Exception
     */
    public function verifyLoginUser($user,$pass){
        try{
            // intentamos hacer el login
            if (\Auth::attempt(['email' =>$user, 'password' =>$pass,'status'=>"A"])){
                ////// credenciales  validas
                
                // ingresamos el intento de login
                $this->__createAttemptLogin($user,$pass,"V");
                
                // ingresamos tipo de login
                $idLast = $this->__createTypeAuth("WEB");
                
                // inicializamos variables de session del usuario
                (new Sessions(\Auth::user()->id))->InicializeSessionVariables($idLast);
                
                return true;
            }else{
                //////// credenciales invalidas
                
                // ingresamos el intento de login
                $this->__createAttemptLogin($user,$pass,"I");
                
                return false;
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * se encarga de actualizar la hora de salida del usuario y
     * cierra la session
     * 
     * @return boolean
     * @throws \Exception
     */
    public function closeSession($classPrincipal){
        try{
            if(isset(\Auth::user()->id)){
                (new Users(null,$classPrincipal))->updateTimeSession();
                // kill session users online
                \Auth::logout();
                
                return true;
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /********************************************************
     * Methodos privados de la clase
     ********************************************************/
    
    /**
     * inserta el intento de login en el sistema
     * 
     * @param type $user
     * @param type $pass
     * @throws \Exception
     */
    private function __createAttemptLogin($user,$pass,$status){
        try{
            (new \App\SyClass\DB\FacCRUD(new \App\SyModels\SysUsersAttemptLogin()))->create(
                (object)array(
                    'user'=>$user,
                    'password'=>$pass,
                    'status'=>$status,
                    'ip_address'=>\Request::getClientIp()
                )
            );
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * inerta el registro en type de login desde hardware
     * 
     * @param type $device
     * @return boolean
     * @throws \Exception
     */
    private function __createTypeAuth($device){
        try{
            $idLast = (new FacCRUD(new \App\SyModels\SysUsersIncome()))
                ->create((object)array(
                    "ip_address" => \Request::getClientIp(),
                    "session_id"=>\Session::getId(),
                    "source_device"=>$device
                ));
            return $idLast;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
