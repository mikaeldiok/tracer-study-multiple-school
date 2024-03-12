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

/*
*
* Auth Routes
*
* --------------------------------------------------------------------
*/
/*
*
* Frontend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Tracer\Http\Controllers\Frontend', 'as' => 'frontend.', 'middleware' => ['web','auth'], 'prefix' => ''], function () {

    /*
     *
     *  Tracers Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'records';
    $controller_name = 'RecordsController';
    Route::get("$module_name/catalog", ['as' => "$module_name.index", 'uses' => "$controller_name@indexPaginated"]);
    Route::get("$module_name/catalog/filter", ['as' => "$module_name.filterRecords", 'uses' => "$controller_name@filterRecords"]);
    Route::get("$module_name/{id}-{recordId}", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);
    Route::get("$module_name/create-sr/{id}", ['as' => "$module_name.createSrRecords", 'uses' => "$controller_name@createSrRecords"]);
});

/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Tracer\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    /*
    * These routes need view-backend permission
    * (good if you want to allow more than one group in the backend,
    * then limit the backend features by different roles or permissions)
    *
    * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
    */

    /*
     *
     *  Records Routes
     *
     * ---------------------------------------------------------------------
     */

    $module_name = 'records';
    $controller_name = 'RecordsController';
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::delete("$module_name/purge/{id}", ['as' => "$module_name.purge", 'uses' => "$controller_name@purge"]);
    Route::post("$module_name/get_record", ['as' => "$module_name.getrecord", 'uses' => "$controller_name@get_record"]);
    Route::post("$module_name/import", ['as' => "$module_name.import", 'uses' => "$controller_name@import"]);
    Route::get("$module_name/create-sr/{id}", ['as' => "$module_name.createSrRecords", 'uses' => "$controller_name@createSrRecords"]);
    Route::resource("$module_name", "$controller_name");


});
