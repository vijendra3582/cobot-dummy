<?php
use \App\Http\Middleware\CheckLogin;
use \App\Http\Middleware\IpMiddleware;
use \App\Fund;
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

Route::get('/underconstruction', function(){
	return view('underconstruction');
})->name('underconstruction');

// Route::group(['middleware' => [IpMiddleware::class]], function () {

/***********************************************************/
Route::group(['prefix'=> 'cms'], function(){
	/** Route to add admin **/
	Route::get('/add-admin','Admin\UserController@addAdmin')->name('create-admin');
	Route::post('/add-admin','Admin\UserController@createAdmin')->name('create-admin');
	/****/
	Route::get('/', function () {
		    return view('cms');
		})->middleware(CheckLogin::class);

	Route::get('/login', 'Admin\UserController@login')->name('cms-login');
	Route::post('/login', 'Admin\UserController@makeLogin')->name('login');
	Route::get('/logout', 'Admin\UserController@logout')->name('logout');

	Route::group(['middleware' => [CheckLogin::class]], function () {
		Route::post('/chunk_upload', 'Admin\UploadController@chunk_upload') ;

		/*** home content routes ***/
		Route::post('/homeContent/edit', 'Admin\HomeController@edit');
		Route::post('/homeContent/save', 'Admin\HomeController@save');
		Route::post('/home/removeBanner', 'Admin\HomeController@removeFile');

		/** About us routes **/
		Route::post('/aboutUs/edit', 'Admin\HomeController@aboutUsEdit');
		Route::post('/aboutUs/save', 'Admin\HomeController@saveAboutUs');
		Route::post('/aboutUs/removeBanner', 'Admin\HomeController@removeAboutUsBanner');
		Route::post('/team/edit', 'Admin\TeamController@editTeamMember');
		Route::post('/team/save', 'Admin\TeamController@saveTeamMemeber');
		Route::post('/team/list/{page}', 'Admin\TeamController@listTeam');
		Route::post('/team/remove-image', 'Admin\TeamController@removeTeamImage');
		Route::post('/team/delete', 'Admin\TeamController@removeMember');
		Route::post('/team/updateStatus', 'Admin\TeamController@updateTeamStatus');
		Route::post('/team/deleteAll', 'Admin\TeamController@deleteAllMembers');
		Route::post('/team/updatePosition', 'Admin\TeamController@updateTeamPosition');
		
		/** news route **/
		Route::post('/news/list/{page}', 'Admin\NewsController@listNews');
		Route::post('/news/save','Admin\NewsController@saveNews');
		Route::post('/news/edit', 'Admin\NewsController@editNews');
		Route::post('/news/remove-image', 'Admin\NewsController@removeImage');
		Route::post('/news/deleteNews','Admin\NewsController@deleteNews');
		Route::post('/news/updateStatus', 'Admin\NewsController@updateStatus');
		Route::post('/news/deleteAll', 'Admin\NewsController@deleteAll');
		Route::post('/news/setForHome', 'Admin\NewsController@setForHome');
		Route::post('/news-position/list', 'Admin\NewsController@newsPositionList');
		Route::post('/news/position', 'Admin\NewsController@savePosition');
		
		Route::post('/newsDisclosure/edit', 'Admin\NewsController@newsDisclosureEdit');
		Route::post('/newsDisclosure/save', 'Admin\NewsController@saveNewsDisclosure');
		Route::post('/newsDisclosure/removeBanner', 'Admin\NewsController@removeNewsDisclosure');
		/** general setting route **/
		Route::post('/generalSetting/edit', 'Admin\GeneralSettingController@editSetting');
		Route::post('/generalSetting/save', 'Admin\GeneralSettingController@saveSetting');
		Route::post('/generalSetting/removeBanner', 'Admin\GeneralSettingController@removeBanner');

		/** contact us route **/
		Route::post('/contactusdb/list/{page}', 'Admin\ContactUsController@listContactUsDB');
		Route::post('/contactusdb/delete', 'Admin\ContactUsController@deleteContactUs');
		Route::post('/contactusdb/deleteAll', 'Admin\ContactUsController@deleteAllContactus');
		Route::post('/export-contact-us', 'Admin\ContactUsController@exportContactus')->name('exportContactUs');

		/** subscribe route **/
		Route::post('/subscribedb/list/{page}', 'Admin\ContactUsController@listSubscribeDB');
		Route::post('/subscribedb/delete', 'Admin\ContactUsController@deleteSubscribe');
		Route::post('/subscribedb/deleteAll', 'Admin\ContactUsController@deleteAllSubscribe');
		Route::post('/export-subscribe', 'Admin\ContactUsController@exportSubscribe')->name('exportSubscribe');

		/** change password **/
		Route::post('/changePassword', 'Admin\UserController@changePassword');

		/** privacy policy **/
		Route::post('/privacypolicy/edit', 'Admin\PrivacyPolicyController@edit');
		Route::post('/privacypolicy/save', 'Admin\PrivacyPolicyController@save');
		Route::post('/privacypolicy/removeBanner', 'Admin\PrivacyPolicyController@removeBanner');

		/** funds routes **/
		Route::post('/funds/list/{page}', 'Admin\FundController@listFunds');
		Route::post('/fund/save', 'Admin\FundController@saveFund');
		Route::post('/fund/edit', 'Admin\FundController@editFund');
		Route::post('/fund/delete', 'Admin\FundController@deleteFund');
		Route::post('/fund/fundStatus', 'Admin\FundController@fundStatus');
		Route::post('/fund/fundDeleteAll', 'Admin\FundController@fundDeleteAll');
		Route::post('/fund/position', 'Admin\FundController@fundPosition');
		Route::post('/fund/removeFile', 'Admin\FundController@removeFile');

		Route::post('/fundContentDisclosure/edit', 'Admin\FundController@getFundContent');
		Route::post('/fundContentDisclosure/save','Admin\FundController@saveFundContent');
		
		Route::post('/fundOutcomeContentDisclosure/edit','Admin\FundController@getFundOutcomeContent');
		Route::post('/fundOutcomeContentDisclosure/save','Admin\FundController@saveFundOutcomeContent');
		Route::post('/fundOutcomeContentDisclosure/removeBanner','Admin\FundController@removeOutcomeBanner'); 


		Route::post('/fund/files', 'Admin\FundFilesController@fundFiles');
		Route::post('/fund/files/save', 'Admin\FundFilesController@saveFundFiles');
		Route::post('/fund/files/delete', 'Admin\FundFilesController@delete');
		Route::post('/fund/files/saveposition','Admin\FundFilesController@savePosition');
		Route::post('/fund/files/removeFiles', 'Admin\FundFilesController@removeFiles');

		Route::post('/fund/details','Admin\FundDataController@fundDetails');
		Route::post('/fund/details/save', 'Admin\FundDataController@saveDetails');
		Route::post('/fund/details/delete', 'Admin\FundDataController@deleteDetail');

		Route::post('/fund/data-and-pricing', 'Admin\FundDataPricingController@details');
		Route::post('/fund/data-and-pricing/save', 'Admin\FundDataPricingController@save');
		Route::post('/fund/data-and-pricing/delete', 'Admin\FundDataPricingController@deleteDetail');

		Route::post('/fund/holdings', 'Admin\FundHoldingsController@details');
		Route::post('/fund/holdings/save' ,'Admin\FundHoldingsController@save');
		Route::post('/fund/holdings/activateFundHolding', 'Admin\FundHoldingsController@activateFundHolding');
		Route::post('/fund/holdings/delete', 'Admin\FundHoldingsController@deleteHoldings');
		Route::post('/fund/holdings/removeHoldingFiles', 'Admin\FundHoldingsController@removeHoldingFiles');

		Route::post('/fund/distribution', 'Admin\FundDistributionController@details');
		Route::post('/fund/distribution/activatateFundDistribution','Admin\FundDistributionController@activatateFundDistribution');
		Route::post('/fund/distribution/delete', 'Admin\FundDistributionController@deleteDistribution');
		Route::post('/fund/distribution/save', 'Admin\FundDistributionController@saveDistribution');
		Route::post('/fund/distribution/removeScheduleFile', 'Admin\FundDistributionController@removeScheduleFile');


		/** outcome period **/
		Route::post('/fund/outcome-period', 'Admin\FundOutcomePeriodController@index');
		Route::post('/fund/outcome-period/save', 'Admin\FundOutcomePeriodController@save');
		Route::post('/fund/outcome-period/delete', 'Admin\FundOutcomePeriodController@delete');
		Route::post('/fund/outcome-period/activateCurrentOutcome', 'Admin\FundOutcomePeriodController@activateCurrentOutcome');
		Route::post('/fund/outcome-period/activateOutcome', 'Admin\FundOutcomePeriodController@activateOutcome');

		Route::post('/fund/current-outcome-period', 'Admin\FundOutcomePeriodController@indexCurrent');
		Route::post('/fund/current-outcome-period/save', 'Admin\FundOutcomePeriodController@saveCurrent');




		Route::post('/fund/performance', 'Admin\FundPerformanceController@fundPerformance');
		Route::post('/fund/performance/save', 'Admin\FundPerformanceController@savePerformance');
		Route::post('/fund/performance/activate', 'Admin\FundPerformanceController@activatePerformance');

		/*** resource routes ***/
		Route::get('/resource/categories/list','Admin\ResourceController@listCategories');

		Route::post('/resource/list/{page}', 'Admin\ResourceController@listResource');
		Route::post('/resource/save', 'Admin\ResourceController@saveResource');
		Route::post('/resource/edit', 'Admin\ResourceController@editResource');
		Route::post('/resource/updatePosition','Admin\ResourceController@savePosition');
		Route::post('/resource/updateStatus', 'Admin\ResourceController@updateStatus');
		Route::post('/resource/deleteAll', 'Admin\ResourceController@deleteAll');
		Route::post('/resource/delete', 'Admin\ResourceController@deleteResource');
		Route::post('/resource/remove-image', 'Admin\ResourceController@removeImage');

		Route::post('/resourceDisclosure/edit', 'Admin\ResourceController@resourceDisclosureEdit');
		Route::post('/resourceDisclosure/save', 'Admin\ResourceController@saveResourceDisclosure');
		Route::post('/resourceDisclosure/removeBanner', 'Admin\ResourceController@removeResourceDisclosure');

		/** site popup **/
		Route::post('/sitepopup','Admin\GeneralSettingController@sitepopup');
		Route::post('/sitepopup/save', 'Admin\GeneralSettingController@saveSitePopup');

		/** interim funds routes **/ 
		Route::post('/interimfund/save', 'Admin\InterimFundController@saveFund');
		Route::post('/interimfund/edit', 'Admin\InterimFundController@editFund');    
		Route::post('/interimfund/files/removeFiles', 'Admin\InterimFundController@removeFile');
		Route::post('/interimfund/removeImage', 'Admin\InterimFundController@removeImage');
		Route::post('/interimfund/files/delete', 'Admin\InterimFundController@deleteFundFile');

	});	
});	

/**** Front end routes ****/

/** for outcome test route**/
/*Route::get('/outcome-period/{fundKey}', function($fundKey){ 
	$fund = Fund::where('url_key',$fundKey)->where('status', config('constants.const_active'))->with('fundData','fundDataPricing','fundDistribution','fundFiles','fundHoldings','fundOutcome','fundCurrentOutcome')->orderBy('position','ASC')->first();
	
	if(empty($fund) || $fund->coming_soon == config('constants.const_active')){
		return redirect()->route('home');
	}
	$fund = $fund->toArray(); 
	 
	return view('fund-outcome-period',compact('fund'));
});*/


/**** Front end routes ****/
Route::get('/', 'Frontend\HomeController@index')->name('home'); 

/** file iframe **/
Route::get('/file/{type}','Frontend\HomeController@fileframe')->name('file-iframe');

/** news route **/
Route::get('/news', 'Frontend\HomeController@news')->name('news');

/** resource list **/
Route::get('/resource', 'Frontend\HomeController@resources')->name('resources-list');


/** privacy policy route **/
Route::get('/privacy-policy', 'Frontend\HomeController@privacyPolicy')->name('privacy-policy');

/** contact us route **/
Route::post('/contact-us','Frontend\HomeController@contactUs')->name('contact-us');

/** subscribe us route **/
Route::post('/subsribe','Frontend\HomeController@subsribe')->name('subscribe-us');

/** downloading holdings route **/
Route::get('/download-holding-usbanks','Frontend\FundController@downloadUSBanksHoldings')->name('download-holding-usbanks');

/** ajax performance route **/
Route::post('/ajax-performance','Frontend\FundController@ajaxPerformance')->name('ajax-performance');


/*
 * Products route
*/
Route::get('/products', 'Frontend\FundController@productList'); 
Route::get('/download-structuredETF-csv','Frontend\FundController@exportStructredETFcsv')->name('download-structuredETF-csv');

/** fund route **/
Route::get('/{fundKey}','Frontend\FundController@indexFund')->name('fund-detail');
Route::get('/{fundKey}/{fundFile}','Frontend\FundController@fundFile')->name('fund-detail-files');







