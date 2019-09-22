<?php
// rutas del create
//////// ruta bienvenida
Route::get('app/inventory/products/create',utilities::getPathFileClean(__FILE__)."\\createProductsController@main"); 
//////// crear producto en bd
Route::put('app/inventory/products/create/savedata',utilities::getPathFileClean(__FILE__)."\\createProductsController@saveDataProduct"); 

// rutas del read
//////// ruta bienvenida
Route::get('app/inventory/products/read',utilities::getPathFileClean(__FILE__)."\\readProductsController@main"); 
//////// get datos productos todos
Route::get('app/inventory/products/read/getdataall',utilities::getPathFileClean(__FILE__)."\\readProductsController@getProductsAll");
//////// get datos de ultimos ingresos
Route::get('app/inventory/products/read/getproductsbycode',utilities::getPathFileClean(__FILE__)."\\readProductsController@getProductsByCode");
//////// get datos de producto by id
Route::get('app/inventory/products/read/{id}/getdataproducbyid',utilities::getPathFileClean(__FILE__)."\\readProductsController@getProductsById");
//////// get datos de ultimos ingresos
Route::get('app/inventory/products/read/getdataproductsbyinventory',utilities::getPathFileClean(__FILE__)."\\readProductsController@getProductsByInventory");

// rutas del update
///// ruta bienvenida update
Route::get('app/inventory/products/update/{id}',utilities::getPathFileClean(__FILE__)."\\updateProductsController@main"); 
///// ruta bienvenida update
Route::post('app/inventory/products/update/savedata',utilities::getPathFileClean(__FILE__)."\\updateProductsController@updateDataProducts"); 

// rutas del delete