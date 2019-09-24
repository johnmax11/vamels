<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        /// create ///
        
        
        /// read ///
        
        // ruta para leer las sales por id
        Route::get(
                "app/clients/management/read/autocomplete",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Management"."Controller@"."getDataClientAutocomplete",
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