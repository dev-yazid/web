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

    /*Route::get('/CaseStudyController/getData', 'Admin\CaseStudyController@getData');
    Route::resource('CaseStudyController', 'Admin\CaseStudyController');*/
    
    Route::get('/job/getData', 'Admin\JobController@getData');
    Route::resource('job', 'Admin\JobController');  
    Route::post('/job/statusChange/', 'Admin\JobController@statusChange');
    
    /*Route::get('/emailtemplates/getData', 'Admin\EmailTemplatesController@getData');
    Route::resource('emailtemplates', 'Admin\EmailTemplatesController');*/

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

    /*Route::post( 'logout','Api\ApiUserController@logout' );  
    Route::post( 'user/forgotPassword','Api\ApiUserController@getForgotPassword' );
    Route::post( 'user/passwordReset','Api\ApiUserController@getPasswordReset' ); 

    Route::get( '/user/getFormToken','Api\ApiController@getFormToken' );
    Route::get( '/user/getByToken','Api\ApiController@getByToken' );
    Route::post( '/user','Api\ApiUserController@users' );*/
    
    //Messages
    /*Route::post( 'user/getAllMessages','Api\ApiUserController@getAllMessages' );
    Route::post( 'user/getNotification','Api\ApiUserController@getNotification' );
    Route::post( 'user/sendMessage','Api\ApiUserController@sendMessage' );
    Route::post( 'user/loadMessages','Api\ApiUserController@getLoadMessages' );
    Route::post( 'user/makeMsgRead','Api\ApiUserController@getMakeMsgRead' );*/

    //Hire or Reject User
    /*Route::post( 'user/hireUser','Api\ApiUserController@getHireUser' );*/
   /* Route::post( 'user/rejectUser','Api\ApiUserController@getRejectUser' );
    Route::post( 'user/quitProject','Api\ApiUserController@getQuitProject' );
    Route::post( 'user/quitProjectFreelancer','Api\ApiUserController@getQuitProjectFreelancer' );
    Route::post( 'user/rejectInv','Api\ApiUserController@rejectUserInv' );
    Route::post( 'user/hiringDetails','Api\ApiUserController@getHiringDetails' );*/

    /* project Close  request*/
    /*Route::post( '/user/projCloseNotification','Api\ApiUserController@projCloseNotification' );
    Route::post( '/user/acceptCloseNotification','Api\ApiUserController@acceptCloseNotification' );
    Route::post( '/user/questionAns ','Api\ApiUserController@questionAns' );*/
    //Route::post( '/user/sendCloseNotification','Api\ApiUserController@sendCloseNotification' );
       

    //Manage Payment 
    /*Route::post( '/payment/paymentProcess','Api\ApiPaymentController@getPaymentProcess' );
    Route::post( '/payment/releasePayment','Api\ApiPaymentController@getReleasePayment' );

    Route::post( '/freelancer/updatePayment','Api\ApiPaymentController@getUpdatePayment' );
    Route::post( '/client/updatePayment','Api\ApiPaymentController@getUpdatePayment' );*/

    // User Module For User [Login, Register, Logout, Forgotpassword]
   /* Route::post( '/user/login', 'Api\ApiUserController@login' );
    Route::post( '/user/register', 'Api\ApiUserController@register' );
    Route::get( '/user/verification/{number}','Api\ApiUserController@verification' );*/
   /* Route::post( '/user/socialLogin', 'Api\ApiUserController@socialLogin' );*/

    //Route::post( '/user/register', 'Api\ApiUserController@register' );    
    /*Route::post( '/user/getAllSkills', 'Api\GlobalController@getAllSkills' );
    Route::post( '/user/getAllCategories', 'Api\GlobalController@getAllCategories' );
    Route::post( '/user/getQualifications', 'Api\GlobalController@getQualifications' );
    Route::post( '/user/getUserDetailsById', 'Api\GlobalController@getUserDetailsById' );*/
    
    // Useing for check Profile and change Profile View
    /*Route::post( '/user/checkUserType', 'Api\GlobalController@checkUserType' );
    Route::post( '/user/changeProfileView', 'Api\GlobalController@changeProfileView' );*/

    //Search And Search AutoCompleate
   // Route::get( '/user/getAutocompleteSkills', 'Api\GlobalController@getAutocompleteSkills' );
   // Route::get( '/user/getAutocompleteLocations', 'Api\LocationController@getAutocompleteLocations' );
    //Route::post( '/search/result', 'Api\HomeController@getSearchResult' );
   // Route::post( '/home/getAllCities', 'Api\HomeController@getAllCities' ); 
    Route::post('/home/getAllCityData','Api\HomeController@getAllCityData');
   // Route::post('/city/getAll');
    //Route::post( '/search/restictresult', 'Api\HomeController@getRestictSearchResult' );
    
    // Common
  /*  Route::post( '/country/getCountries', 'Api\LocationController@getCountries' );
    Route::post( '/country/getStates', 'Api\LocationController@getStates' );
    Route::post( '/country/getCities', 'Api\LocationController@getCities' ); 
    Route::post( '/user/getAllLocations', 'Api\LocationController@getAllLocations' );*/
    
   /* Route::post( '/page/pageDetails', 'Api\GlobalController@getPageDetails' );
    Route::post( '/blog/getAllBlogs', 'Api\GlobalController@getAllBlogs' );
    Route::post( '/home/getTopRatedJobs', 'Api\HomeController@getTopRatedJobs' );
    Route::post( '/home/newsletterSubscribe', 'Api\HomeController@getNewsletterSubscribe' );*/
    
    //Route::post( '/home/searchProject', 'Api\HomeController@getSearchResult' ); 
    /*Route::post( '/home/getAllCaseStudy', 'Api\HomeController@getAllCaseStudy' );
    Route::post( '/home/getAllCommunity', 'Api\HomeController@getAllCommunity' );  */  

    // Freelancer   
   /* Route::post( '/freelancer/file_upload', 'Api\ApiFreelancerController@file_upload' ); 
    Route::post( '/freelancer/updateFreelancer', 'Api\ApiFreelancerController@postUpdateFreelancer' );
    Route::post( '/freelancer/getFreelancerDetails', 'Api\ApiFreelancerController@getFreelancerDetails' );
    Route::post( '/freelancer/updateCompany', 'Api\ApiFreelancerController@postUpdateCompany' );
    Route::post( '/freelancer/changePasssword', 'Api\ApiFreelancerController@postChangePasssword' );
    Route::post( '/freelancer/getAllProjects', 'Api\ApiFreelancerController@getAllProjects' );
    Route::post( '/freelancer/projectDetailsById', 'Api\ApiFreelancerController@getProjectDetailsById' );
    Route::post( '/freelancer/getSkillsByArray', 'Api\ApiFreelancerController@getSkillsByArray' );
    Route::post( '/freelancer/getMyProposals', 'Api\ApiFreelancerController@getMyProposals' );
    Route::post( '/freelancer/getMyInvitations', 'Api\ApiFreelancerController@getMyInvitations' );
    Route::post( '/freelancer/getMyProjects', 'Api\ApiFreelancerController@getMyProjects' );
    Route::post( '/freelancer/changeInvStatus', 'Api\ApiFreelancerController@changeInvStatus' );
    Route::post( '/freelancer/getFreelancerProjectStatus', 'Api\ApiFreelancerController@getFreelancerProjectStatus' );   */

    //Job Apply
   /* Route::post( '/freelancer/apply_job', 'Api\ApiFreelancerController@apply_job' );
    Route::post( '/freelancer/checkInformation', 'Api\ApiFreelancerController@checkInformation' );
    Route::post( '/freelancer/getCheckJobApplied', 'Api\ApiFreelancerController@getCheckJobApplied' );
    Route::post( '/freelancer/saveProposal', 'Api\ApiFreelancerController@postSaveProposal' );
    Route::post( '/freelancer/updateProposal', 'Api\ApiFreelancerController@postUpdateProposal' );*/
   
    // Client
    /*Route::post( '/client/file_upload', 'Api\ApiClientController@file_upload' ); 
    Route::post( '/client/profile_upload', 'Api\ApiClientController@profile_upload' );    
    Route::post( '/client/remove_image', 'Api\ApiClientController@remove_file_image' );    
    Route::post( '/client/clientData', 'Api\ApiClientController@postClientData' );
    Route::post( '/client/updateClient', 'Api\ApiClientController@postUpdateClient' );
    Route::post( '/client/jobDocuments', 'Api\ApiClientController@jobDocuments' );
    Route::post( '/client/jobPic', 'Api\ApiClientController@jobPic' );
    Route::post( '/client/postProject', 'Api\ApiClientController@postPostProject' );
    Route::post( '/client/getMyPostedProjects', 'Api\ApiClientController@getMyPostedProjects' );
    Route::post( '/client/getProjectStatusByClientId', 'Api\ApiClientController@getProjectStatusByClientId' );
    Route::post( '/client/sentProjectInvitation', 'Api\ApiClientController@postSentProjectInvitationToFreelancer' );
    Route::post( '/client/sendFinalProposal', 'Api\ApiClientController@sendFinalProposal' );*/

    // Project Data
    /*Route::post( '/client/projectDetailsById', 'Api\ApiClientController@getProjectDetailsById' );
    Route::post( '/client/getProjectReviewById', 'Api\ApiClientController@getProjectReviewById' );*/
    
    
});
