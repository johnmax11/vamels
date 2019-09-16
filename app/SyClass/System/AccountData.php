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
class AccountData {
    //put your code here
    
    private $_idUser;
    private $_arrDataAccount;
    public function __construct($idUser) {
        $this->_idUser = $idUser;
        // consultamos los datos de account data
        $this->__getAccountDataByIdUser();
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
            
            $obj = (new \App\SyClass\DB\FacCRUD(new \App\SyModels\SysAccountData()));
            $this->_arrDataAccount = $obj->read(array(array('users_id',$this->_idUser)));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
