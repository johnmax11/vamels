<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        // agrupacion de rutas
        Route::group(['prefix' => 'app/consolidate/scrutiny'], function() {
            
            /// create ///
        
            
            
            /// read ///

            // ruta para leer las witnesses
            Route::get(
                    "read",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Scrutiny"."Controller@"."main",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );
            
            // ruta para recuperas las zonas por ciudad
            Route::get(
                    "read/zones",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Scrutiny"."Controller@"."getDataZonesByIdCity",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );
            
            // ruta para recuperas los puestos por zona
            Route::get(
                    "read/places",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Scrutiny"."Controller@"."getDataPlazesByIdZones",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );
            
            // ruta para recuperas los tables por puesto
            Route::get(
                    "read/tables",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Scrutiny"."Controller@"."getDataTablesByIdPlaces",
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
            
        
        });
        
    }
}