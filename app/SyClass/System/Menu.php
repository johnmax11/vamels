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
 * Description of Menu
 *
 * @author johnm
 */
class Menu {
    //put your code here
    public function __construct($objInterfaceResponse) {
        // verificamos si hay interface de respuesta
        if($objInterfaceResponse != null){
            $this->_interfaceResponse = $objInterfaceResponse;
        }
    }
    
    /**
     * arma el html del menu de la izquierda
     * 
     * @return type
     * @throws \Exception
     */
    public function constructMenuIzqByRole(){
        try{
            $arrDM = (new Security())->readAccessSecurityProgramsByRole();
            
            $strH = '<ul class="collapsible" data-collapsible="accordion">';
            $cR = count($arrDM);
            
            if($cR>0){
                $strH .= '  <li class="bold '.(\Session::get('module')=="home"?'active':'').'">';
                $strH .= '     <a href="'.\Config::get('syslab.path_url_web').'/app/home/main/read" class="waves-effect waves-cyan active">';
                $strH .= '        <i class="material-icons">home</i>';
                $strH .= '        <span class="nav-text">Inicio</span>';
                $strH .= '     </a>';
                $strH .= '  </li>';
                
                //echo '--or-->'.print_r(\Session::get('module'),true);
                
                
                for($i=1;$i<$cR;$i++){
                    // sacamos el nombre del modulo
                    $arrDModulo = (new FacCRUD(new \App\SyModels\SysSecurityAccessModules($arrDM[$i]->security_access_modules_id)));
                    //echo '--or-->'.print_r($arrDModulo->get()->name,true);
                    $strH .= '  <li class="bold">';
                    $strH .= '     <a id="'.$arrDModulo->get()->name.'-1" class="collapsible-header waves-effect waves-cyan">';
                    $strH .= '        <i class="material-icons">'.$arrDModulo->get()->icon_image.'</i>';
                    $strH .= '        <span class="nav-text">'.$arrDModulo->get()->name_alias.'</span>';
                    $strH .= '     </a>';
                    
                    $strH .= '  <div class="collapsible-body">';
                    $strH .= '      <ul>';
                    
                    $contTmp = $i;
                    $idTmp = $arrDM[$i]->security_access_modules_id;
                    $strH_Tmp = '';
                    while(true){
                        // sacamos el nombre del action
                        $arrDPrograms = (new FacCRUD(new \App\SyModels\SysSecurityAccessPrograms($arrDM[$contTmp]->security_access_programs_id)));
                        $strH_Tmp .= '          <li '.(\Session::get('action')==$arrDPrograms->get()->name?'class="active"':'').'>';
                        $strH_Tmp .= '              <a href="'.\Config::get('syslab.path_url_web').'/app/'.$arrDModulo->get()->name.'/'.$arrDPrograms->get()->name.'/read">';
                        $strH_Tmp .= '                  <i class="material-icons">keyboard_arrow_right</i>';
                        $strH_Tmp .= '                  <span> '.$arrDPrograms->get()->name_alias.'</span>';
                        $strH_Tmp .= '              </a>';
                        $strH_Tmp .= '          </li>';
                        $contTmp ++;
                        if(!isset($arrDM[$contTmp]) || $arrDM[$contTmp]->security_access_modules_id != $idTmp){
                            $i = ($contTmp-1);
                            break;
                        }
                    }
                    // agregamos al div interno el html
                    $strH .= $strH_Tmp;
                    // cerramos los tags
                    $strH .= '      </ul>';
                    $strH .= '  </div>';
                    
                    $strH .= '  </li>';
                }
            }
            $strH .= '</ul>';
            return $this->_interfaceResponse->callBackResponse(array(
                "html"=>$strH,
                "module"=>\Session::get('module'),
                "action"=>\Session::get('action')
            ));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    
}
