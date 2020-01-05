<?php
namespace App\Http\Controllers\App\Sys_Tmpmiscelaneos\prTmpMiscelaneos;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\Helpers\Response;
use App\SyClass\System\Security;
use App\SyClass\System\Interfaces\InterfaceResponse;

class uploadTmpMiscelaneosController extends Controller
                            implements InterfaceResponse{
    
    public function __construct() {}
    
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function main(){}
    
    /**
     * carga una archivo en la tabla generica
     * 
     * @author john jairo cortes garcia <john.cortes@syslab.so>
     * @created_at 02-05-2016
     * @return type
     * @throws \Exception
     */
    public function processUploadFileGeneric(){
        try{
            $nameField = \Request::input("nameField");

            //if (\Request::hasFile('txtFile')){
            if (count(\Request::file($nameField))==0){
                return $this->callBackResponse(array('msgResponseFirst'=>'El archivo no subio correctamente'),true);
            }
            
            // borramos todo lo de este user
            $objFacTmpMiscelaneos = new \App\SyClass\System\TmpMiscelaneos();
            $objFacTmpMiscelaneos->setDeleteTmpMiscelaneos(array(
                array('syslab_session_id',(\Session::get('sys_users_income_id')!=null ?\Session::get('sys_users_income_id') :null)),
                array('parameters',$nameField),
                array('sys_users_id',\Auth::user()->id)
            ));
            
            
            // movemos los archivos
            $arrDataFiles = array();
            for($i=0;$i<count(\Request::file($nameField));$i++){
                $path = \Request::file($nameField)[$i]->getRealPath();
                $name = \Request::file($nameField)[$i]->getClientOriginalName();
                $extension = \Request::file($nameField)[$i]->getClientOriginalExtension();
                $size = round(((\Request::file($nameField)[$i]->getSize()/1024)/1024),2);
                $mime = \Request::file($nameField)[$i]->getMimeType();
            
                // validamos el size del file
                if($size > 10){
                    return $this->callBackResponse(
                            array(
                                'msgResponseFirst'=>'Error validando el tamaño del archivo',
                                'msgResponseDetails'=>array('size archivo'=>array(array('message'=>'El tama&ntilde;o del archivo es mas grande que 10Mb','type'=>'error')))
                            ),
                            true);
                }
                
                ////////////////////////////////////////////////////////////////
                // movemos el archivo a la carpeta tmp
                $uuid = Utilities::create_guid();
                $ext_r = "png";
                if($extension != 'png' && $extension != 'jpg' && $extension!='gif'){
                    $ext_r = $extension;
                }
                $nfile = @date('Ymd-His').'_'.$uuid.'.'.$ext_r;
                \Request::file($nameField)[$i]->move(public_path().'/tmp',$nfile);
                /*****************************************************************/
                
                /**insertamos en la tabla tmp*/
                $objFacTmpMiscelaneos = new \App\SyClass\System\TmpMiscelaneos();
                $idLast = $objFacTmpMiscelaneos->setInsertTmpMiscelaneos((object)array(
                    "syslab_session_id"=>(\Session::get('sys_users_income_id')!=null ?\Session::get('sys_users_income_id') :null),
                    "sys_users_id"=>\Auth::user()->id,
                    "data_principal"=>$nfile,
                    "data_secundario"=>$name,
                    "parameters"=>$nameField
                ));
                
                $arrDataFiles[] = array(
                    'name'=>$name,
                    'size'=>$size,
                    'name_bd'=>$nfile,
                    'id_bd'=>$idLast
                );
                
            } // fin for
            return $this->callBackResponse(
                    array(
                        'msgResponseFirst'=>(count(\Request::file($nameField))>1?
                                'Yeah! Los archivos se cargar&oacute;n sin problemas!':
                                'Yeah! El archivo cargo sin problemas ;)')
                    )
            );
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /*********************************++
     * interface response json
     * 
     * @param type $jsonResponse
     * @return type
     * @throws \Exception
     ************************************/
    public function callBackResponse($jsonResponse = null,$error=false,$type="success") {
        try{
            return (new Response())->responseRequest($jsonResponse,$error,$type);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
