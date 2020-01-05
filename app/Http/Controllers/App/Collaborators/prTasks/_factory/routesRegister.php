<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        // agrupacion de rutas
        Route::group(['prefix' => 'app/collaborators/tasks'], function() {
            
            /// create ///
        
            /// read ///

            /// update ///
            // pagina inicio modulo
            Route::get(
                    "update/{id}",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\update"."Tasks"."Controller@"."main",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );
            
            /// delete ///
            // remueve un registro de witnesses
            Route::delete(
                    "delete/{idtask}",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\delete"."Tasks"."Controller@"."deleteTaskImageById",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );

            /// upload ///
            // retorna el contenido de una imagen del witnesses
            Route::post(
                    "upload/filegeneric",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\upload"."Tasks"."Controller@"."uplImagenTasks",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );
            
            /// download ///
            
            // retorna el contenido de una imagen del witnesses task
            Route::get(
                    "download/{idtask}/{imagen}",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\download"."Tasks"."Controller@"."getImagenTask",
                        "as"=>null,
                        "middleware"=>["auth","permissionrole"],
                        "where"=>[],
                        "domain"=>null
                    ]
            );
            
        });
        
    }
}