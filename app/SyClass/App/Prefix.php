<?php
namespace App\Facades;
namespace App\Facades\App;
/**
 * @author john jairo cortes garcia <johnmax11@hotmail.com>
 * @created_at 26-08-2017
 */
class FacPrefix{
    
    private $_orClass;
    public function __construct($orClass){
        $this->_orClass = $orClass;
    }
    /**
     * consulta todos los prefix del sistema
     * 
     * @return type
     * @throws \Exception
     */
    public function getPrefixAll(){
        try{
            $objFacC = new \App\Facades\FacCRUD(new \CategoryProducts());
            $arrData = $objFacC->read(
                null,
                null,
                array(array('prefix','ASC')),
                null,
                array('id','name','prefix')
            );
            
            $arrRsp = array();
            $nR = count($arrData);
            for($i=0;$i<$nR;$i++){
                $arrRsp[$i] = new \stdClass();
                
                $arrRsp[$i]->id = $arrData[$i]->id;
                $arrRsp[$i]->name = $arrData[$i]->prefix." - ".$arrData[$i]->name;
            }
            
            // set response
            return $this->_orClass->callBackResponse($arrRsp);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}