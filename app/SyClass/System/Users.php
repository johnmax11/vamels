<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SyClass\System;
use \App\SyClass\DB\FacCRUD;
/**
 * Description of Users
 *
 * @author johnm
 */
class Users {
    private $_idUser;
    private $_interfaceResponse;
    
    /**
     * constructor
     * 
     * @param type $idUSer
     * @param type $objInterfaceResponse
     * @return type
     */
    public function __construct($idUSer,$objInterfaceResponse) {
        
        // verificamos si el valor es diferente a null
        if($idUSer != null){
            $this->_idUser = $idUSer;
        }
        
        // verificamos si hay interface de respuesta
        if($objInterfaceResponse != null){
            $this->_interfaceResponse = $objInterfaceResponse;
        }
    }
    
    /**
     * retorna el objetc del user
     * 
     * @return type
     * @throws \Exception
     */
    public function getObject(){
        try{
            return (new FacCRUD(new \App\SyModels\SysUsers($this->_idUser)));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }


    /**
     * agrega registro en el track del usuario
     * 
     * @return boolean
     * @throws \Exception
     */
    public function createTrack(){
        try{
            (new FacCRUD(new \App\SyModels\SysUsersTrack()))
                ->create((object)array(
                    'sys_users_income_id'=>session('sys_users_income_id'),
                    'url_track'=>\Request::url(),
                    'ip_address'=>\Request::getClientIp(),
                    'request_data'=>serialize($_REQUEST),
                    'request_headers'=>serialize(\Request::header())
                ));
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * actualiza el tiempo de session de el usuario logueado
     * 
     * @throws \Exception
     */
    public function updateTimeSession(){
        try{
            (new FacCRUD(new \App\SyModels\SysUsersIncome()))
                ->update(
                        (object)array("timestamp_exit" => @date('Y-m-d H:i:s')),
                        (object)array("id" => \Session::get('sys_users_income_id')
                ));
            return $this->_interfaceResponse->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}
