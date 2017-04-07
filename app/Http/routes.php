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

    /* common Api */
    //Route::post('/user/getAllCities','Api\UserController@getAllCities');
     
    /* Brodcast Request */
    Route::post('/brodcast/brodcastInitData','Api\BrodcastController@getBrodcastInitData');    
    Route::post('/brodcast/newProductRequest','Api\BrodcastController@sendNewProductRequest');
    Route::post('/brodcast/allBrodRequest','Api\BrodcastController@getAllBrodRequest');
    Route::post('/brodcast/productsByBrandId','Api\BrodcastController@getProductsByBrandId');  

    /* Buyer Management */

    /* Before Logged In */
    Route::post('/user/registerMobile','Api\UserController@getRegisterMobile');
    Route::post('/user/sendCodeAgain','Api\UserController@getSendCodeAgain');
    Route::post('/user/verifyMobile','Api\UserController@getVerifyMobile');    
    Route::post('/user/updateProfile','Api\UserController@getUpdateProfile');
    Route::post('/user/userLogin','Api\UserController@getUserLogin');
    Route::post('/user/buyerRegisterInit','Api\UserController@getBuyerRegisterInit');

    /* After Logged In */
    Route::post('/user/myProfilUpdate','Api\UserController@getUpdateProfileByUser');
    Route::post('/user/myProfileDetails','Api\UserController@getMyProfileDetails');
    Route::post('/user/viewBrodreqByUser','Api\UserController@getViewRequestByUser');
    Route::post('/user/viewResponse','Api\UserController@getViewResponse');
    Route::post('/user/removeResponse','Api\UserController@getRemoveResponse');
    Route::post('/user/markPriceNotiRead','Api\UserController@getMarkPriceReadUpdateNoti');
    Route::post('/user/changePassword','Api\UserController@getChangePassword');   
    Route::post('/user/logout','Api\UserController@getLogout');

    /* Seller Management*/
      
});
