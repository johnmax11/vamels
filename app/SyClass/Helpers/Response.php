<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SyClass\Helpers;

/**
 * Description of Response
 *
 * @author johnm
 */
class Response {
    //put your code here
    
    public function __construct() {
    }
    
    /**
     * metodo q retorna la respuesta en formato json
     * 
     * @param type $response
     * @param type $error
     * @param type $type
     * @return type
     * @throws \Exception
     */
    public function responseRequest(
            $response = null,
            $error=false,
            $type='success'){
        try{
            if($response == null){
                $response = array('msgResponseFirst'=>"Siii! Tu tranquilo, todo finalizo correctamente");
            }
            
            // retorno
            return \Response::json(
                array(
                    "msg"=>$response,
                    "error"=>$error,
                    "type_msg"=>$type
            ));
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * ordena los errores retornados pro laravel
     * 
     * @param type $strDetails
     * @param type $type
     * @return type
     * @throws \Exception
     */
    public function orderDetails($strDetails,$type=null){
        try{
            foreach ($strDetails as $key => $value) {
                for($i=0;$i<count($value);$i++){
                    $strDetails[$key][$i] = (array('message'=>$value[$i],'type'=>$type));
                }
            }
            return $strDetails;
        }catch(\Exception $ex){
            throw $ex;
        }
    }
}
