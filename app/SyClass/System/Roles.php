<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SyClass\System;
use \App\SyClass\DB\FacCRUD;
/**
 * Description of Roles
 *
 * @author johnm
 */
class Roles {
    
    
    /**
     * retorna los datos de un rol por id rol user
     * 
     * @param type $idRoleUser
     * @return type
     * @throws \Exception
     */
    public function getDataRoleByIdSecurityRole($idRoleUser){
        try{
            $objRole = (new FacCRUD(new \App\SyModels\SysSecurityRoles($idRoleUser)));
            return $objRole->get();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
