<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        // rutas create
        // rutas read
        /////// retorna el contenido de un file js del sistema
        Route::get(
                "getfilejs/{file}",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readFilesController@getFileJsSystem",
                    "as"=>null,
                    "middleware"=>["auth"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        /////// retorna el contenido de un file js del sistema
        Route::get(
                "getfilejs/app/views/js/{module}/{action}/{file}",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readFilesController@getFileJsResources",
                    "as"=>null,
                    "middleware"=>["auth"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        // rutas update
        // rutas delete
        
    }
}