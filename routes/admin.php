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
define('PAGINATION_COUNT',10);
Route::group(['middleware'=>'auth:admin' , 'namespace'=>'Admin'],function (){
        Route::get('/'  , 'DashboardController@index')->name('admin.dashboard');

  ######################################## begin languages ##########################################################
        Route::group(['prefix'=> 'languages'], function ()
        {
            Route::get('/' , 'LanguagesController@index')->name('admin.languages');
            Route::get('/create' , 'LanguagesController@create')->name('admin.languages.create');
            Route::post('/store' , 'LanguagesController@store')->name('admin.languages.store');
            Route::get('edit/{id}' , 'LanguagesController@edit')->name('admin.languages.edit');
            Route::patch('update/{id}' , 'LanguagesController@update')->name('admin.languages.update');
            Route::get('delete/{id}' , 'LanguagesController@destroy')->name('admin.languages.delete');



        });
    ######################################## end  languages ##########################################################


    ######################################## begin Main Categories ##########################################################
    Route::group(['prefix'=> 'main_categories'], function ()
    {
        Route::get('/' , 'MainCategoriesController@index')->name('admin.maincategories');
        Route::get('/create' , 'MainCategoriesController@create')->name('admin.maincategories.create');
        Route::post('/store' , 'MainCategoriesController@store')->name('admin.maincategories.store');
        Route::get('edit/{id}' , 'MainCategoriesController@edit')->name('admin.maincategories.edit');
        Route::patch('update/{id}' , 'MainCategoriesController@update')->name('admin.maincategories.update');
        Route::get('delete/{id}' , 'MainCategoriesController@destroy')->name('admin.maincategories.delete');
         Route::get('change_status/{id}' , 'MainCategoriesController@status')->name('admin.maincategories.changestatus');


    });
    ######################################### end Main Categories #########################################################



     ######################################## begin vendors ##########################################################
     Route::group(['prefix'=> 'vendors'], function ()
     {
         Route::get('/' , 'VendorsController@index')->name('admin.Vendors');
         Route::get('/create' , 'VendorsController@create')->name('admin.Vendors.create');
         Route::post('/store' , 'VendorsController@store')->name('admin.Vendors.store');
         Route::get('edit/{id}' , 'VendorsController@edit')->name('admin.Vendors.edit');
         Route::patch('update/{id}' , 'VendorsController@update')->name('admin.Vendors.update');
         Route::get('delete/{id}' , 'VendorsController@destroy')->name('admin.Vendors.delete');
         Route::get('change_status/{id}' , 'VendorsController@status')->name('admin.Vendors.change_status');

     });
 ######################################## end  languages ##########################################################



});

Route::group(['middleware'=>'guest:admin' , 'namespace'=>'Admin'],function (){

    Route::get('/login' , 'LoginController@getLogin')->name('get.admin.login');;
    Route::post('/login' , 'LoginController@Login')->name('admin.login');

 });

