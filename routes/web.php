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

Route::get('/', function () {
    return view('welcome');
});
Route::post('/country/store','CountryController@store')->name('country.store');
Route::get('/country/index','CountryController@index')->name('country.index');
Route::get('/country/edit/{id}','CountryController@edit')->name('country.edit');
Route::post('/country/post','CountryController@update')->name('country.update');
Route::get('/country/delete/{id}','CountryController@delete')->name('country.delete');
Route::post('/bulk/country-delete','CountryController@bulk_delete')->name('bulk.delete');
