<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SyClass\System;

use \App\SyClass\DB\FacCRUD;
use \App\SyModels;
/**
 * Description of TmpMiscelaneos
 *
 * @author johnm
 */
class TmpMiscelaneos {
    
    public function __construct() {}
    
    /**
     * insertamos en tmp miscelaneos
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @param type $arrParametros
     * @throws \Exception
     */
    public function setInsertTmpMiscelaneos($arrParametros = null){
        try{
            $objModel = new SyModels\SysTmpMiscelaneos();
            return $objModel->sCreate($arrParametros);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * funcion para borrar d tmpmiscelaneos
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 02-05-2016
     * @param type $arrParameters
     * @throws \Exception
     */
    public function setDeleteTmpMiscelaneos($arrParameters = null){
        try{
            $objModel = new SyModels\SysTmpMiscelaneos();
            $objModel->sDelete($arrParameters);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * consulta la tabla tmpmiscelaneos
     * 
     * @param type $arrWhere
     * @param type $arrGroupBy
     * @param type $arrOrderBy
     * @param type $arrLimit
     * @return type
     * @throws \Exception
     */
    public function getTmpMiscelaneos($arrWhere=null, $arrGroupBy=null, $arrOrderBy=null, $arrLimit=null){
        try{
            $objModel = new SyModels\SysTmpMiscelaneos();
            return $objModel->sRead($arrWhere, $arrGroupBy, $arrOrderBy, $arrLimit);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * muevo la imagen por parametros
     * 
     * @param type $syslab_ses_id
     * @param type $parameters
     * @param type $pathTarget
     * @param type $factorRes
     * @param type $factorHe
     * @return type
     * @throws \Exception
     */
    public function moveImageByParams(
        $syslab_ses_id,
        $parameters,
        $pathTarget,
        $factorRes=null,
        $factorHe=null,
        $useFnName=null
    ){
        try{
            $objFacCrud = new FacCRUD(new SyModels\SysTmpMiscelaneos());
            $dataImg = $objFacCrud->read(array(
                array('syslab_session_id',$syslab_ses_id),
                array('parameters',$parameters)
            ));
            
            
            if(count($dataImg)>0){
                // resize imagen
                \Image::make(public_path().'/tmp/'.$dataImg[0]->data_principal,array(
                    'width' => $factorRes,
                    'height'=>$factorHe
                ))->save(public_path().'/tmp/'.$dataImg[0]->data_principal);
                
                // verificamos folder
                $this->verifyCreateFolder($pathTarget);
                
                //vericamos si custom name img
                $nameFile = $dataImg[0]->data_principal;
                if(!is_null($useFnName)){
                    $ext = substr($nameFile,strrpos($nameFile,"."));
                    $nameFile = ($useFnName.$ext);
                }
                
                // copiamos la imagen
                $res = copy(
                            public_path().'/tmp/'.$dataImg[0]->data_principal,
                            storage_path().'/app/files/'.$pathTarget."/".$nameFile
                        );
                return $dataImg[0]->data_principal;
            }
            
            return null;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @param type $pathTarget
     * @return boolean
     * @throws \Exception
     */
    public function moveImageSinImagen($pathTarget,$nimagen=null){
        try{
            // verificamos folder
            $this->verifyCreateFolder($pathTarget);
            if($nimagen != null){
                $res = copy(public_path().'/images/sin_imagen.png',storage_path().'/app/files/'.$pathTarget."/".$nimagen);
            }else{
                // copiamos la imagen
                $uuid = \App\SyClass\Helpers\Utilities::create_guid();
                $nfile = @date('Ymd-His').'_'.$uuid.'.png';
                $res = copy(public_path().'/images/sin_imagen.png',storage_path().'/app/files/'.$pathTarget."/".$nfile);
            }
            return $nfile;    
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * mueve un documento
     * 
     * @param type $syslab_ses_id
     * @param type $parameters
     * @param type $pathTarget
     * @return type
     * @throws \Exception
     */
    public function moveDocumentByParams($syslab_ses_id,$parameters,$pathTarget){
        try{
            $objFacCrud = new FacCRUD(new SyModels\SysTmpMiscelaneos());
            $dataImg = $objFacCrud->read(array(
                array('syslab_session_id',$syslab_ses_id),
                array('parameters',$parameters)
            ));
            
            if(count($dataImg)>0){
                // verificamos folder
                $this->verifyCreateFolder($pathTarget);
                // copiamos la imagen
                $res = copy(public_path().'/tmp/'.$dataImg[0]->data_principal,storage_path().'/app/files/'.$pathTarget."/".$dataImg[0]->data_principal);
                return $dataImg[0]->data_principal;
            }
            
            return null;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @param type $path
     * @return boolean
     * @throws \Exception
     */
    public function verifyCreateFolder($path){
        try{
            // verificamos para crear la carpeta 
            $folderD = storage_path().'/app/files/'.$path;
            if(!file_exists($folderD)){
                // creamos la carpeta
                mkdir($folderD, 0755, true);
            }
            
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * 
     * @param type $path
     * @return type
     * @throws \Exception
     */
    public function deleteImageByPath($path){
        try{
            if (file_exists(storage_path().'/app/files/'.$path)) {
                if(is_file(storage_path().'/app/files/'.$path)){
                    return unlink(storage_path().'/app/files/'.$path);
                }
            }
            
            return true;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * renombra una archivo por path
     * 
     * @param type $pathOld
     * @param type $pathNew
     * @return boolean
     * @throws \Exception
     */
    public function renameFolderByPath($pathOld,$pathNew){
        try{
            if(is_dir(storage_path().'/app/files/'.$pathOld)){
                return rename(storage_path().'/app/files/'.$pathOld, storage_path().'/app/files/'.$pathNew);
            }
            return false;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}
