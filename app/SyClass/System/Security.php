<?php

/**
 * se encarga de manejar la logica del menu izquierdo
 * 
 * author: JJCortes <johnmax11@hotmail.com>
 * date: 09-01-2017
 */

namespace App\SyClass\System;
use \App\SyClass\DB\FacCRUD;
/**
 * Description of Security
 *
 * @author johnm
 */
class Security {
    //put your code here
    public function __construct() {}
    
    /**
     * retorna los datos del menu by role user
     * 
     * @return type
     * @throws \Exception
     */
    public function readAccessSecurityProgramsByRole(){
        try{
            // consultamos las opcion del menu por id usuario
            return (new FacCRUD(new \App\SyModels\SysSecurityAccessModulesPrograms()))
                ->read(
                        array(
                            array("security_roles_id",\Session::get('name_security_roles_id')),
                            array('display','Y'),
                            array('display_home','Y')
                        ),
                        null,
                        array(array("order_module","ASC"),array("order_program","ASC"))
                );
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * saca los datos de recurso q envia al view
     * 
     * @return type
     * @throws \Exception
     */
    public function readDataRecursosView(){
        try{
            /**
                [0] => app
             *  [1] => module
             *  [2] => action
             *  [3] => crud
             *  [4] => detalle_action
             */
            $arrRout = $this->getCurrentUrl();
            
            // sacamos los datos del module
            $arrDModule = (new FacCRUD(new \App\SyModels\SysSecurityAccessModules()))
                                ->read(array(array('name',$arrRout[1])));
            
            // sacamos los datos del programs
            $arrDPrograms = (new FacCRUD(new \App\SyModels\SysSecurityAccessPrograms()))
                                ->read(array(array('name',$arrRout[2])));
            
            // set data recurso en session
            $arrRsp = array(
                'crud'=>$arrRout[3],
                'module'=>$arrDModule[0]->name,
                'action'=>$arrDPrograms[0]->name,
                'n_module'=>$arrDModule[0]->name_alias,
                'n_action'=>$arrDPrograms[0]->name_alias,
                'icon'=>$arrDPrograms[0]->icon_image,
                'short_description'=>$arrDPrograms[0]->short_description,
            );
            
            // set variables de recurso
            (new Sessions(null))->InicializeSessionRecursoUrl((object)$arrRsp);
            
            return $arrRsp;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * verifica el permiso del rol de usuario con los permisos para el action
     * 
     * @return int
     * @throws \Exception
     */
    public function verifyPermissionByRole(){
        try{
            $arrRout = $this->getCurrentUrl();
            switch (count($arrRout)){
                case 4:
                case 5:
                case 6:
                    // sacamos los datos del module
                    $arrDModule = (new FacCRUD(new \App\SyModels\SysSecurityAccessModules()))
                                        ->read(array(array('name',$arrRout[1])));
            
                    // sacamos los datos del programs
                    $arrDPrograms = (new FacCRUD(new \App\SyModels\SysSecurityAccessPrograms()))
                                        ->read(array(array('name',$arrRout[2])));
                    
                    if(count($arrDModule)> 0 && count($arrDPrograms)>0){
                        // consultamos los permisos
                        $arrDPermission = (new FacCRUD(new \App\SyModels\SysSecurityAccessModulesPrograms()))
                                        ->read(array(
                                            array('security_access_modules_id',$arrDModule[0]->id),
                                            array('security_access_programs_id',$arrDPrograms[0]->id),
                                            array('security_roles_id',\Auth::user()->security_roles_id)
                                        ));
                        if(count($arrDPermission)>0){
                            // verificamos el permiso
                            switch($arrRout[3]){
                                case "create":
                                    if($arrDPermission[0]->create_c == "Y"){
                                        return 1;
                                    }
                                    break;
                                case "read":
                                    if($arrDPermission[0]->read_c == "Y"){
                                        return 1;
                                    }
                                    break;
                                case "update":
                                    if($arrDPermission[0]->update_c == "Y"){
                                        return 1;
                                    }
                                    break;
                                case "delete":
                                    if($arrDPermission[0]->delete_c == "Y"){
                                        return 1;
                                    }
                                    break;
                                case "upload":
                                    if($arrDPermission[0]->upload_c == "Y"){
                                        return 1;
                                    }
                                    break;
                                case "download":
                                    if($arrDPermission[0]->download_c == "Y"){
                                        return 1;
                                    }
                                    break;
                            }
                            return -1;
                        }else{
                            return -1;
                        }
                        
                        return 1;
                    }else{
                        return -1;
                    }
                    
                    break;
                    
                default:
                    return -2;
            }
            
            return 1;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * retorna la ruta de la url limpia
     * 
     * @return type
     * @throws \Exception
     */
    private function getCurrentUrl(){
        try{
            $strR = substr(url()->current(),strpos(url()->current(),"public")+7);
            $arrRout = explode("/",$strR);
            
            return $arrRout;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
