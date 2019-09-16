<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        ////////// CREATE ///////////////////
        
        
        ////////// READ //////////////////
        
        // verifica los evento y los envia
        Route::get(
                "app/cron/jobs/read/verifyagendaevent",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\updateJobsController@verifyAgendaEvent",
                    "as"=>null,
                    "middleware"=>[],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        
        ///////// UPDATE ////////////////////
        
        ///////// DELETE ///////////////////
        
        ///////// UPLOAD ///////////////////
        
        ///////// DOWNLOAD /////////////////
        
        
    }
}