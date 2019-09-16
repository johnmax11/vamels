<?php
use App\SyClass\Helpers\Utilities;
class routesRegister{
    public function __construct() {
        // pagina inicio modulo
        Route::get(
                "app/home/main/read",
                [
                    "uses"=>Utilities::getPathFileClean(__FILE__)."\\readMainController@main",
                    "as"=>null,
                    "middleware"=>["auth","permissionrole"],
                    "where"=>[],
                    "domain"=>null
                ]
        );
    }
}