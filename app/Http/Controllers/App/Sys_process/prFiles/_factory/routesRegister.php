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
        
        /////// retorna el contenido de una imagen del evento
        Route::get(
                "imgevent/{idevento}/{imagen}",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readFilesController@getFileImagenEvento",
                    "as"=>null,
                    "middleware"=>[],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        /////// retorna el contenido del video
        Route::get(
                "videos/videos_sponsor/{video}",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readFilesController@getFileVideoSponsor",
                    "as"=>null,
                    "middleware"=>[],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        /////// retorna el contenido de una imagen del evento
        Route::get(
                "upload/images_upload/{imagen}",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readFilesController@getFileImagenUploadEvento",
                    "as"=>null,
                    "middleware"=>[],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        /////// retorna el contenido de una imagen de sponsor
        Route::get(
                "imgspon/img_spon/{imagen}",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readFilesController@getFileImagenSponsor",
                    "as"=>null,
                    "middleware"=>[],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        
        // rutas update
        // rutas delete
        
    }
}