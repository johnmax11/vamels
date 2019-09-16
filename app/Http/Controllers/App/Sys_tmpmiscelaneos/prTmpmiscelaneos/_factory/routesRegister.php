<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        ////////// CREATE ///////////////////
        
        ////////// READ //////////////////
        
        ///////// UPDATE ////////////////////
        
        ///////// DELETE ///////////////////
        
        ///////// UPLOAD ///////////////////
        
        // pagina inicio programa
        Route::post(
                "app/sys_tmpmiscelaneos/tmpmiscelaneos/upload/filegeneric",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\uploadTmpMiscelaneosController@processUploadFileGeneric",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        ///////// DOWNLOAD /////////////////
        
        
    }
}