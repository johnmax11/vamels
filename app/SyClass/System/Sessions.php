<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SyClass\System;
use \App\SyClass\DB\FacCRUD;
/**
 * Description of Sessions
 *
 * @author johnm
 */
class Sessions {
    private $_idUser;
    
    public function __construct($idUSer) {
        $this->_idUser = $idUSer;
    }

    /**
     * inicializa las variables de session del usuario actual
     * 
     * @return boolean
     * @throws \Exception
     */
    public function InicializeSessionVariables($idUsIncome){
        try{
            // set data session
            \Session::put('users_income_id',$idUsIncome);
            // sacamos los datos del rol de usuario
            $arrDataRoles = (new Roles())->getDataRoleByIdSecurityRole(\Auth::user()->security_roles_id);
            // set data session
            \Session::put('id',$this->_idUser);
            \Session::put('name_security_roles_id', $arrDataRoles->id);
            \Session::put('name_security_roles', $arrDataRoles->name);
            // consultamos los datos de account data
            $arrDAccData = (new AccountData(\Auth::user()->id))->getAccountData();
            // set data session
            \Session::put('account_data_id',$arrDAccData[0]->id);
            \Session::put('photo_profile',$arrDAccData[0]->photo_profile);
            \Session::put('first_name',$arrDAccData[0]->first_name);
            \Session::put('last_name',$arrDAccData[0]->last_name);
            
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * inicializa las avaribales de modulo y action
     * 
     * @param type $data
     * @throws \Exception
     */
    public function InicializeSessionRecursoUrl($data){
        try{
            \Session::put('module',$data->module);
            \Session::put('action',$data->action);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
