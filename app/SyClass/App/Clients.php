<?php
namespace App\Facades;
namespace App\Facades\App;
/**
 * @author john jairo cortes garcia <johnmax11@hotmail.com>
 * @created_at 26-08-2017
 */
class FacClients{
    
    private $_orClass;
    public function __construct($orClass){
        $this->_orClass = $orClass;
    }
    
    /**
     * consulta los clientes de la base de datos
     * 
     * @return type
     * @throws \Exception
     */
    public function getAllClientsGrid(){
        try{
            $objFacCr = new \App\Facades\FacCRUD(new \Clients());
            $arrD = $objFacCr->read(array(array('status','A')));
            
            // response
            return $this->_orClass->callBackResponse($arrD);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * busca los productos por el codigo
     * 
     * @param type $origenClass
     * @param type $strSearch
     * @return type
     * @throws \Exception
     */
    public function getClientsByAutoComplete($strSearch){
        try{
            //////// buscamos por codigo producto
            $objFacCrud = new \App\Facades\FacCRUD(new \Clients());
            $arrDCli = $objFacCrud->read(
                array(array('name','LIKE',"%".$strSearch."%")),
                null,
                array(array('name','ASC')),
                null,
                array('id','name','phone')
            );
            
            /////// verificamos si hay resultados
            if(count($arrDCli) == 0){
                // buscamos por el nombre producto
                $objFacCrud = new \App\Facades\FacCRUD(new \Clients());
                $arrDCli = $objFacCrud->read(
                    array(array('phone','LIKE',"%".$strSearch."%")),
                    null,
                    array(array('phone','ASC')),
                    null,
                    array('id','name','phone')
                );
            }
            
            // set response
            return $this->_orClass->callBackResponse($arrDCli);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * crea un cliente nuevo
     * 
     * @param type $dataRequest
     * @return type
     * @throws \Exception
     */
    public function setCreateClienteNew($dataRequest){
        try{
            // validamos si el cliente esta creado
            $objFacC = new \App\Facades\FacCRUD(new \Clients());
            $arrD = $objFacC->read(array(array('phone',$dataRequest->txtTelefono)));
            
            if(count($arrD)>0){
                // response
                $objResponse = new \App\Helpers\ResponseCustom();
                return $objResponse->json(
                    array(
                        'msgResponseFirst'=>"Cliente(".$arrD[0]->name.") con(".$dataRequest->txtTelefono.") ya esta registrada en la base de datos",
                      ),true,'warning'
                );
            }else{
                $objFacC->create((object)array(
                    'name'=>$dataRequest->txtNombreCompleto,
                    'phone'=>$dataRequest->txtTelefono
                ));
            }
            
            // set response
            return $this->_orClass->callBackResponse();
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}