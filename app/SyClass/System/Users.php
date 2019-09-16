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
        $this->_idUser = $idUSer;
        
        // verificamos si el valor es diferente a null
        if($this->_idUser != null){
            return (new FacCRUD(new \App\SyModels\SysUsers($this->_idUser)));
        }
        // verificamos si hay interface de respuesta
        if($objInterfaceResponse != null){
            $this->_interfaceResponse = $objInterfaceResponse;
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
                    'url_track'=>\Request::url(),
                    'ip_address'=>\Request::getClientIp()
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
                        (object)array("id" => \Session::get('users_income_id')
                ));
            return $this->_interfaceResponse->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}
