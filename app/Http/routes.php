<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

header("Access-Control-Allow-Origin: *");

View::addExtension('html', 'php');

Route::get('/', function()
{
    return View::make('index');
});

/**
 * Admin routes
 */
Route::get('/admin', 'Auth\AuthController@login'); 

Route::group(array('prefix' => 'admin', 'middlewareGroups' => 'web', 'before' => 'auth'), function() {
    
    Route::auth();    
    Route::get('/', function() { return Redirect::to("admin/login"); });
    Route::resource('dashboard', 'Admin\DashboardController');

    /*Route::get('/categories/getData', 'Admin\CategoriesController@getData');
    Route::resource('categories', 'Admin\CategoriesController');

    Route::get('/skills/getData', 'Admin\SkillsController@getData');
    Route::resource('skills', 'Admin\SkillsController');*/

    Route::get('/product/getData', 'Admin\ProductController@getData');
    Route::resource('product', 'Admin\ProductController');

    Route::get('/user/getData', 'Admin\UserController@getData');
    Route::resource('user', 'Admin\UserController');
    Route::get('/user/inactiveActiveUser/{id}', 'Admin\UserController@inactiveActiveUser');

    Route::get('/response/getData', 'Admin\ResponseController@getData');
    Route::resource('response', 'Admin\ResponseController');

    Route::get('/request/getData', 'Admin\RequestController@getData');
    Route::resource('request', 'Admin\RequestController');

    Route::get('/transaction/getData', 'Admin\TransactionController@getData');
    Route::resource('transaction', 'Admin\TransactionController');

    Route::get('/brand/getData', 'Admin\BrandController@getData');
    Route::resource('brand', 'Admin\BrandController');

       
    Route::get('/job/getData', 'Admin\JobController@getData');
    Route::resource('job', 'Admin\JobController');  
    Route::post('/job/statusChange/', 'Admin\JobController@statusChange');
    
    Route::get('/language/getData', 'Admin\GlobelController@getData');
    Route::get('/language/refresh', 'Admin\GlobelController@refresh');
    Route::any('/language/updateLabel', 'Admin\GlobelController@updateLabel');
    Route::resource('language', 'Admin\GlobelController');

    Route::get('/country/getData', 'Admin\CountryController@getData');
    Route::resource('country', 'Admin\CountryController');

    Route::get('/state/getData', 'Admin\StateController@getData');
    Route::resource('state', 'Admin\StateController');

    Route::get('/city/getData', 'Admin\CityController@getData');
    Route::resource('city', 'Admin\CityController');
      Route::get('/banner/getData', 'Admin\BannerController@getData');
    Route::resource('banner','Admin\BannerController');

});

 /**
 * For API
 */
Route::group(['prefix' => 'api','middleware' => ['api','web'], 'before' => 'auth'], function() {
    Route::auth();

    
    Route::post('/home/getAllCityData','Api\HomeController@getAllCityData');
    
    
});
