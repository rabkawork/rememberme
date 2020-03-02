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

Route::get('/', function () {
    return view('items');
});

// Route::get('/', 'ItemsController@index')->name('items.index');
Route::any('data', 'ItemsController@data')->name('items.data');
Route::any('add', 'ItemsController@add')->name('items.add');
Route::any('updateHistory', 'ItemsController@updateHistory')->name('items.updateHistory');
Route::any('deleteItems', 'ItemsController@deleteItems')->name('items.delete');
