<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SyClass\App;
use \App\SyClass\DB\FacCRUD;
use \App\SyModels;

/**
 * Description of Sports
 *
 * @author johnm
 */
class Sports {
    
    private $_interfaceResponse;
    
    /**
     * 
     * @param type $objInterfaceResponse
     */
    public function __construct($objInterfaceResponse) {
        // verificamos si hay interface de respuesta
        if($objInterfaceResponse != null){
            $this->_interfaceResponse = $objInterfaceResponse;
        }
    }
    
    /**
     * retorna los datos de los deportes
     * 
     * @return type
     * @throws \Exception
     */
    public function getDataSports(){
        try{
            $arrData = (new FacCRUD(new SyModels\ParamsSports()))
                            ->read(array(array("id",">",0)));
            
            return $this->_interfaceResponse->callBackResponse(array("rows"=>$arrData),false,"not-alert");
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
