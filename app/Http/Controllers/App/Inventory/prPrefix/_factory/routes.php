<?php
// rutas del create

// rutas del read
//////// get datos productos todos
Route::get('app/inventory/prefix/read/getdataprefix',utilities::getPathFileClean(__FILE__)."\\readPrefixController@getPrefixAll");

// rutas del update

// rutas del delete