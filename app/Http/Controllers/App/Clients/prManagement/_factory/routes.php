<?php
// rutas del create
//////// ruta bienvenida
Route::get('app/clients/management/create',utilities::getPathFileClean(__FILE__)."\\createManagementController@main");
//////// ruta bienvenida
Route::put('app/clients/management/create/savedata',utilities::getPathFileClean(__FILE__)."\\createManagementController@saveDataClients");

// rutas del read
//////// ruta bienvenida
Route::get('app/clients/management/read',utilities::getPathFileClean(__FILE__)."\\readManagementController@main");
//////// ruta consultar clientes
Route::get('app/clients/management/read/getdataall',utilities::getPathFileClean(__FILE__)."\\readManagementController@getDataAllClients");
//////// ruta consultar clientes bu autocomplete
Route::get('app/clients/management/read/getbyautocomplete',utilities::getPathFileClean(__FILE__)."\\readManagementController@getClientsByAutocomplete");

// rutas del update

// rutas del delete