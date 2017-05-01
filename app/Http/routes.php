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
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Authorization ,Content-Type, Accept");


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

    Route::get('/product/getData', 'Admin\ProductController@getData');
    Route::resource('product', 'Admin\ProductController');

    Route::get('/user/getData', 'Admin\UserController@getData');
    Route::resource('user', 'Admin\UserController');
    Route::post('/user/statusChange', 'Admin\UserController@statusChange');

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

    
    Route::post('/app/appInitData','Api\UserController@getAppInitData');
    
    /* Brodcast Request */
    Route::post('/brodcast/brodcastInitData','Api\BrodcastController@getBrodcastInitData');    
    Route::post('/brodcast/newProductRequest','Api\BrodcastController@sendNewProductRequest');   
    Route::post('/brodcast/productsByBrandId','Api\BrodcastController@getProductsByBrandId');    
    Route::post('/brodcast/updateProductRequest','Api\BrodcastController@getUpdateProductRequest');  

    /* Buyer Management */

    /* Before Logged In */
    Route::post('/user/registerMobile','Api\UserController@getRegisterMobile');
    Route::post('/user/sendCodeAgain','Api\UserController@getSendCodeAgain');
    Route::post('/user/verifyMobile','Api\UserController@getVerifyMobile');    
    Route::post('/user/updateProfile','Api\UserController@getUpdateProfile');
    Route::post('/user/userLogin','Api\UserController@getUserLogin');
    Route::post('/user/cityList','Api\UserController@getBuyerRegisterInit');
   
    /*
       Not In Used
       Route::post('/user/getAllCities','Api\UserController@getAllCities');      
       Route::post('/user/getVerifyCode','Api\UserController@getCurrentVerificationCode'); 
       Route::post('/brodcast/allBrodRequest','Api\BrodcastController@getAllBrodRequest');
       Route::post('/user/myProfilUpdate','Api\UserController@getUpdateProfileByUser');
       Route::post('/seller/isSellerApproved','Api\SellerController@getSellerApprovedByAdmin');
       Route::post('/seller/refreshtoken','Api\SellerController@refreshtoken');
    */

    /* After Logged In */
    Route::post('/user/logout','Api\UserController@getLogout');    
    Route::post('/user/myProfileDetails','Api\UserController@getMyProfileDetails');
    Route::post('/user/viewBrodreqByUser','Api\UserController@getViewRequestByUser');
    Route::post('/user/viewResponse','Api\UserController@getViewResponse');
    Route::post('/user/removeRequest','Api\UserController@getRemoveRequest');
    Route::post('/user/markPriceNotiRead','Api\UserController@getMarkPriceReadUpdateNoti');
    Route::post('/user/changePassword','Api\UserController@getChangePassword');
    Route::post('/user/productConfirmedByBuyer','Api\UserController@getProductConfirmedByBuyer');
    Route::post('/user/removeResponse','Api\UserController@getRemoveResponse');

    /* Seller Management*/

    /* Before Logged In */
    Route::post('/seller/sellerRegister','Api\SellerController@getRegisterSeller');
    Route::post('/seller/sellerLogin','Api\SellerController@getSellerLogin');    
    Route::post('/seller/sendMobileVeriCodeAgain','Api\SellerController@getSendMobileVerifyCodeAgain');
    Route::post('/seller/mobileVerify','Api\SellerController@getMobileVerify');
    Route::post('/seller/sellerChangedPassword','Api\SellerController@getChangePasswordSeller'); 
    
    /* After Logged In */
    Route::post('/seller/sellerDetails','Api\SellerController@getSellerDetails');
    Route::post('/seller/allBrodRequest','Api\SellerController@getAllBrodRequests');    
    Route::post('/seller/updateSeller','Api\SellerController@getUpdateSellerProfile');
    /*Route::post('/seller/viewRequestDeatils','Api\SellerController@getRequestDeatils'); */ 
    /*Route::post('/seller/productConfirmedBySeller','Api\SellerController@getProductConfirmedBySeller');*/
    /*Route::post('/seller/updateResponse','Api\SellerController@getUpdateResponseBySeller');*/
    Route::post('/seller/sendResponse','Api\SellerController@getSendResponse');
   
    /* Messages chat */
    Route::post('/chat/message','Api\MessageController@sendMessage');
    Route::post('/chat/getAllMessages','Api\MessageController@getAllMessages');

    /* Cronjob */
    Route::post('/cronjob/vcodeExpires','Api\MessageController@vcodeExpires');
    Route::post('/cronjob/notificationAdmin','Api\MessageController@notificationAdmin');
      
});
