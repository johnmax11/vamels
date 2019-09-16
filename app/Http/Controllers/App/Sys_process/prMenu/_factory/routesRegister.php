<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        // rutas create
        // rutas read
        /////// retorna el contenido del menu izquierdo
        Route::get(
                "app/sys_process/menu/read/getmenui",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readMenuController@getHtmlMenuIzq",
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