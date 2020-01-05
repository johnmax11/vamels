<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        // agrupacion de rutas
        Route::group(['prefix' => 'app/collaborators/witnesses'], function() {
            
            /// create ///
        
            // pagina inicio modulo
            Route::get(
                    "create",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\create"."Witnesses"."Controller@"."main",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );

            // ruta para guardar los datos
            Route::post(
                    "create/data",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\create"."Witnesses"."Controller@"."saveData",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );

            /// read ///

            // ruta para leer las witnesses
            Route::get(
                    "read",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Witnesses"."Controller@"."main",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );

            // ruta para leer las sales por id
            Route::get(
                    "read/data/{id}",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Witnesses"."Controller@"."getDataById",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );

            // ruta para leer los witnesses por cualquier campo
            Route::get(
                    "read/data",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Witnesses"."Controller@"."getData",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );
            
            // ruta para leer los witnesses por cualquier campo
            Route::get(
                    "read/statistics",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Witnesses"."Controller@"."getStatistics",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );

            /// update ///
            // pagina inicio modulo
            Route::get(
                    "update/{id}",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\update"."Witnesses"."Controller@"."main",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );
            
            // actualiza datos de witnesses
            Route::put(
                    "update/data/{id}",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\update"."Witnesses"."Controller@"."updateData",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );

            /// delete ///
            // remueve un registro de witnesses
            Route::delete(
                    "delete/{idwitnesses}",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\delete"."Witnesses"."Controller@"."deleteWitnesses",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );

            /// upload ///
            
            /// download ///
            // retorna el contenido de una imagen del witnesses
            Route::get(
                    "download/{idwitnesses}/{imagen}",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\download"."Witnesses"."Controller@"."getImagen",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );
        
        
        });
        
    }
}