<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'App\Http\Controllers\CategoryController@index')->name('index_category');
Route::post('/category/create', 'App\Http\Controllers\CategoryController@create')->name('create_category');
Route::post('/category/autocomplete', 'App\Http\Controllers\CategoryController@autocompleteSearch')->name('autocompleteSearch');
