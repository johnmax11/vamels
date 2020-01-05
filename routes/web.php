<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

date_default_timezone_set('America/Bogota');

// login usuario
Route::get('login', 'Auth\LoginController@main')->name('login');
Route::get('/login', 'Auth\LoginController@main')->name('login');
Route::get('/', 'Auth\LoginController@main')->name('login');

// cerrar session
Route::get('/logout', 'Auth\LoginController@logOutSystem');

// validate inicio de session
Route::post('/auth', 'Auth\LoginController@authLogin');

Route::get('404', function(){
    return View::make('errors.404');
});

Route::group(array(['middleware' => ['web']],'before' => 'auth'), function(){
    
    // rutas modulo Home
    ////////// ruta main
    $strR = substr(url()->current(),strpos(url()->current(),"public")+7);
    $arrRoute = explode("/",$strR);
    switch(count($arrRoute)){
        case 4:
        case 5:
        case 6:
            if(file_exists(app_path("Http/Controllers/App/".ucfirst($arrRoute[1])."/pr".ucfirst($arrRoute[2])."/_factory/routesRegister.php"))){
                // include del file de routes q se necesita
                require app_path("Http/Controllers/App/".ucfirst($arrRoute[1])."/pr".ucfirst($arrRoute[2])."/_factory/routesRegister.php");
                (new routesRegister());
            }else{
                return redirect('errors.routeinvalid');
            }
            
            break;
        case 3:
            // carga de archivos js sistema
            require app_path("Http/Controllers/App/Sys_process/prFiles/_factory/routesRegister.php");
            (new routesRegister());
            
            break;
        case 2:
        case 7:
            // carga de archivos js sistema
            require app_path("Http/Controllers/App/Sys_process/prFiles/_factory/routesRegister.php");
            (new routesRegister());
            
            break;
    } // cierree del switch
    
    Route::get('permitsdenied', function(){
        return View::make('errors.permitsdenied');
    });
    Route::get('routerinvalid', function(){
        return View::make('errors.routerinvalid');
    });
   
});
