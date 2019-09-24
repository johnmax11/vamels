<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        /// create ///
        
        // pagina inicio modulo
        Route::get(
                "app/box/sales/create",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\create"."Sales"."Controller@"."main",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        // ruta para guardar los datos
        Route::put(
                "app/box/sales/create/savedata",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\create"."Sales"."Controller@"."saveDataServer",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        /// read ///
        
        // ruta para leer las sales
        Route::get(
                "app/box/sales/read",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Sales"."Controller@"."main",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        // ruta para leer las sales por id
        Route::get(
                "app/box/sales/read/data/{id}",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Sales"."Controller@"."getDataSaleById",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        // ruta para leer las sales por id
        Route::get(
                "app/box/sales/read/data",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Sales"."Controller@"."getDataSales",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        
        /// update ///
        
        /// delete ///
        
        /// upload ///
        /// download ///
    }
}