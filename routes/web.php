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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('article')->group(function () {

    Route::get('','ArticleController@index')->name('article.index');
    Route::post('storeAjax', 'ArticleController@storeAjax')->name('article.storeAjax');
    Route::get('editAjax/{article}', 'ArticleController@editAjax')->name('article.editAjax');
    Route::post('updateAjax/{article}', 'ArticleController@updateAjax')->name('article.updateAjax');
    Route::post('deleteAjax/{article}', 'ArticleController@destroyAjax' )->name('article.destroyAjax');
    Route::get('showAjax/{article}', 'ArticleController@showAjax')->name('article.showAjax');
    Route::post('destroySelected', 'ArticleController@destroySelected')->name('article.destroySelected');



});

Route::prefix('type')->group(function () {

    Route::get('','TypeController@index')->name('type.index');
    Route::post('storeAjax', 'TypeController@storeAjax')->name('type.storeAjax');
    Route::get('editAjax/{type}', 'TypeController@editAjax')->name('type.editAjax');
    Route::post('updateAjax/{type}', 'TypeController@updateAjax')->name('type.updateAjax');
    Route::post('deleteAjax/{type}', 'TypeController@destroyAjax' )->name('type.destroyAjax');
    Route::get('showAjax/{type}', 'TypeController@showAjax')->name('type.showAjax');


});
