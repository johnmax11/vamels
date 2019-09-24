<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccountData
 *
 * @author johnm
 */
namespace App\SyClass\System;
use \App\SyClass\DB\FacCRUD;

class AccountData {
    //put your code here
    
    private $_idUser;
    private $_arrDataAccount;
    private $_interfaceResponse;
    
    public function __construct($idUser, $objInterfaceResponse) {
        
        if($idUser != null){
            $this->_idUser = $idUser;
            // consultamos los datos de account data
            $this->__getAccountDataByIdUser();
        }
        // verificamos si hay interface de respuesta
        if($objInterfaceResponse != null){
            $this->_interfaceResponse = $objInterfaceResponse;
        }
    }
    
    /**
     * retorna los datos de un account data
     * 
     * @return type
     * @throws \Exception
     */
    public function getAccountData(){
        try{
            return $this->_arrDataAccount;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta los datos de un account data por id user
     * 
     * @throws \Exception
     */
    private function __getAccountDataByIdUser(){
        try{
            $obj = (new FacCRUD(new \App\SyModels\SysAccountData()));
            $this->_arrDataAccount = $obj->read(array(array('users_id',$this->_idUser)));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * retorna los porfiles de los usuarios existentes
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataProfiles(){
        try{
            // consultamos todos los profiles
            $objFacAccD = (new FacCRUD(new \App\SyModels\SysAccountData()));
            $arrDataAccData = $objFacAccD->read(array(array("id",">",0)));
            
            // set response
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrDataAccData), false, "not-alert");
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
