<?php
// rutas de create
///// ruta para leer las create
Route::get('app/inventory/warehouse/create',utilities::getPathFileClean(__FILE__)."\\createWarehouseController@main");
///// ruta para leer las create
Route::put('app/inventory/warehouse/create/savedata',utilities::getPathFileClean(__FILE__)."\\createWarehouseController@saveDataInventory");

// rutas del read
///// ruta para leer las sales
Route::get('app/inventory/warehouse/read',utilities::getPathFileClean(__FILE__)."\\readWarehouseController@main");
///// ruta para leer todos el inventario
Route::get('app/inventory/warehouse/read/getdataall',utilities::getPathFileClean(__FILE__)."\\readWarehouseController@getDataWarehouse");
//////// get datos de producto by id
Route::get('app/inventory/warehouse/read/{id}/getinventory',utilities::getPathFileClean(__FILE__)."\\readWarehouseController@getInventoryProductsById");
///// ruta para leer el detalle del track
Route::get('app/inventory/warehouse/read/getdetailstrack',utilities::getPathFileClean(__FILE__)."\\readWarehouseController@getDetailsTrack");

// rutas del update
///// ruta para leer las update
Route::get('app/inventory/warehouse/update/{id}',utilities::getPathFileClean(__FILE__)."\\updateWarehouseController@main");
///// ruta para guardar la actualizacion
Route::post('app/inventory/warehouse/update/savedata',utilities::getPathFileClean(__FILE__)."\\updateWarehouseController@saveDataUpdateInventory");


// rutas del delete

// rutas download

