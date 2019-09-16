<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        
        ////////// CREATE ///////////////////
        
        // pagina inicio programa
        Route::get(
                "app/aplication/events/create",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\createEventsController@main",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        // guarda los datos del evento
        Route::put(
                "app/aplication/events/create/savedataevent",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\createEventsController@saveDataEvent",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        ////////// READ //////////////////
        
        // pagina inicio programa
        Route::get(
                "app/aplication/events/read",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readEventsController@main",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        // carga los datos de la grilla
        Route::get(
                "app/aplication/events/read/getdatagrid",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readEventsController@getDataGridPrincipal",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        // carga los datos de los deportes
        Route::get(
                "app/aplication/events/read/getdatasports",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readEventsController@getDataSports",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        // carga los datos del evento
        Route::get(
                "app/aplication/events/read/getdataevent",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readEventsController@getDataEvent",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        ///////// UPDATE ////////////////////
        
        // guarda los datos del evento
        Route::post(
                "app/aplication/events/update/updateeventofacebook",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\updateEventsController@updatePostFacebookEvento",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
        
        
        ///////// DELETE ///////////////////
        
        ///////// UPLOAD ///////////////////
        
        ///////// DOWNLOAD /////////////////
        
        
    }
}