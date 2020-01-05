<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        // agrupacion de rutas
        Route::group(['prefix' => 'app/params/places'], function() {
            
            /// create ///
            /// read ///

            // ruta para leer las sales por id
            Route::get(
                    "read/autocomplete",
                    [
                        "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Places"."Controller@"."autocomplete",
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