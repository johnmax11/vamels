<?php
namespace App\Http\Controllers\App\Sys_process\prFiles;

use Illuminate\Routing\Controller;
use App\SyClass\Helpers\Utilities;
use App\SyClass\System\Interfaces\InterfaceResponse;

class readFilesController extends Controller
                            implements InterfaceResponse{

    private $_objResponse;
    private $_objFvs;
    
    public function __construct() {}
    
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function main(){
        try{
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * retorna el contenido de un archivo js
     * 
     * @param type $mod
     * @param type $act
     * @param type $file
     * @return type
     * @throws \Exception
     */
    public function getFileJsSystem($file){
        try{
            $filepath = base_path() . '/resources/views/app/_templates/js/' . $file;
            //echo $filepath;exit;
            return \Response::download($filepath);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * retorna el contenido de un archivo js
     * 
     * @param type $mod
     * @param type $act
     * @param type $file
     * @return type
     * @throws \Exception
     */
    public function getFileJsResources($mod,$act,$file){
        try{
            //$filepath = base_path() . '/resources/views/app/'.$mod.'/pr'.ucfirst(strtolower($pro)).'/js/' . $fileName;
            //echo $filepath;exit;
            //return \Response::download($filepath);
            
            
            $filepath = base_path() . '/resources/views/app/'.$mod.'/pr'.ucfirst(strtolower($act)).'/js/' . $file;
            $strFile = file_get_contents($filepath);
            
            //header('Content-Type: application/javascript; charset=UTF-8', true);
            //echo $strFile;
            //return \Response::download($filepath);
            return \response($strFile)->header('Content-Type', 'application/javascript');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
	
	/**
     * 
     **/
    public function getFileImagenEvento($idevento,$imagen){
        try{
            // carga de archivos img del sistema
            $path = storage_path() . '/app/files/events/'.$idevento.'/'. $imagen; // Podés poner cualquier ubicacion que quieras dentro del storage

            if(!\File::exists($path)) abort(404); // Si el archivo no existe
    
            $file = \File::get($path);
            $type = \File::mimeType($path);
    
            $response = \Response::make($file, 200);
            $response->header("Content-Type", $type);
    
            return $response;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
	
	/** **/
    public function getFileVideoSponsor($video){
        try{
            // carga de archivos img del sistema
            $path = storage_path() . '/app/files/videos_sponsor/'.$video; // Podés poner cualquier ubicacion que quieras dentro del storage

            if(!\File::exists($path)) abort(404); // Si el archivo no existe
    
            $file = \File::get($path);
            $type = \File::mimeType($path);
    
            $response = \Response::make($file, 200);
            $response->header("Content-Type", $type);
    
            return $response;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
	/****/
    public function getFileImagenSponsor($imagen){
        try{
            // carga de archivos img del sistema
            $path = storage_path() . '/app/files/images_sponsor/'.$imagen; // Podés poner cualquier ubicacion que quieras dentro del storage

            if(!\File::exists($path)) abort(404); // Si el archivo no existe
    
            $file = \File::get($path);
            $type = \File::mimeType($path);
    
            $response = \Response::make($file, 200);
            $response->header("Content-Type", $type);
    
            return $response;
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
