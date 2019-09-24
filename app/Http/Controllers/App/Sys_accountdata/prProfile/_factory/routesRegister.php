<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        /// create ///
        
        /// read ///
        
        // ruta para leer las sales por id
        Route::get(
                "app/sys_accountdata/profile/read/data",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\read"."Profile"."Controller@"."getDataProfiles",
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