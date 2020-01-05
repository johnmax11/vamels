<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        // rutas create
        // rutas read
        // rutas update
        // rutas delete
        /////// actualiza varios procesos del sistema
        Route::put(
                "app/sys_process/various/update/process",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\updateVariousController@executeProcess",
                    "as"=>null,
                    "middleware"=>["auth"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
    }
}