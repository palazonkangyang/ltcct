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


	Route::post('/auth/login', ['as' => 'auth-page', 'uses' => 'AuthController@postAuthenticate']);
  Route::get('/auth/login', ['uses' => 'AuthController@authenticate']);

  Route::get('/auth/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
	Route::get('/', ['as' => 'login-page', 'uses' => 'AdminController@login']);

 	Route::get('/login', ['as' => 'login-page', 'uses' => 'AdminController@login']);
 	Route::get('/logout', ['as' => 'logout-page', 'uses' => 'AdminController@logout']);

	// Post Route
	Route::post('/login', ['as' => 'post-login-page', 'uses' => 'AdminController@postLogin']);


Route::group(['middleware' => 'auth'], function () {

	Route::group(['prefix' => 'admin'], function () {

		// Get Route
		Route::get('/dashboard', ['as' => 'dashboard-page', 'uses' => 'AdminController@dashboard']);
    Route::get('/add-account', ['as' => 'add-account-page', 'uses' => 'AdminController@getAddAccount']);
		Route::get('/all-accounts', ['as' => 'all-accounts-page', 'uses' => 'AdminController@getAllAccounts']);
		Route::get('/account/edit/{id}', ['as' => 'all-account-page', 'uses' => 'AdminController@getEditAccount']);
		Route::get('/account/delete/{id}', ['as' => 'all-account-page', 'uses' => 'AdminController@deleteAccount']);
		Route::post('/add-account', ['as' => 'add-account-page', 'uses' => 'AdminController@postAddAccount']);
		Route::post('/change-account', ['as' => 'change-account-page', 'uses' => 'AdminController@changeAccount']);
  });

  Route::group(['prefix' => 'operator'], function () {

		Route::get('/index', ['as' => 'main-page', 'uses' => 'OperatorController@index']);
		Route::get('/search/autocomplete', ['as' => 'search-autocomplete-page', 'uses' => 'OperatorController@getAutocomplete']);
		Route::get('/search/autocomplete2', ['as' => 'search-autocomplete2-page', 'uses' => 'OperatorController@getAutocomplete2']);
		Route::get('/search/address_street', ['as' => 'search-address-street-page', 'uses' => 'OperatorController@getAddressStreet']);
		Route::get('/address-translate', ['as' => 'address-translate-page', 'uses' => 'OperatorController@getAddressTranslate']);
		Route::get('/devotee/edit/{devotee_id}', ['as' => 'edit-devotee-page', 'uses' => 'OperatorController@getEditDevotee']);
		Route::match(["post", "get"], '/devotee/new-search', ['as' => 'get-json-focus-devotee-page', 'uses' => 'OperatorController@getRemoveFocusDevotee']);
		Route::post('/devotee/search-familycode', ['as' => 'search-familycode-page', 'uses' => 'OperatorController@getSearchFamilyCode']);
		Route::get('/devotee/getDevoteeDetail', ['as' => 'get-devotee-page', 'uses' => 'OperatorController@getDevoteeDetail']);
		Route::get('/devotee/getMemberDetail', ['as' => 'get-devotee-page', 'uses' => 'OperatorController@getMemberDetail']);
		Route::get('/devotee/focus-devotee', ['as' => 'get-json-focus-devotee-page', 'uses' => 'OperatorController@getJSONFocusDevotee']);
		Route::get('/devotee/delete/{devotee_id}', ['as' => 'delete-devotee-page', 'uses' => 'OperatorController@deleteDevotee']);

		Route::match(["post", "get"], '/focus-devotee', ['as' => 'focus-devotee-page', 'uses' => 'OperatorController@getFocusDevotee']);
		Route::post('/relocation', ['as' => 'relocation-devotee-page', 'uses' => 'OperatorController@postRelocationDevotees']);
		Route::post('/new-devotee', ['as' => 'new-devotee-page', 'uses' => 'OperatorController@postAddDevotee']);
		Route::post('/edit-devotee', ['as' => 'edit-devotee-page', 'uses' => 'OperatorController@postEditDevotee']);
  });

  Route::group(['prefix' => 'staff'], function () {
    Route::get('/search-devotee', ['as' => 'search-devotee-page', 'uses' => 'StaffController@getSearchDevotee']);
    Route::get('/donation', ['as' => 'get-donation-page', 'uses' => 'StaffController@getDonation']);
    Route::get('/receipt/{receipt_id}', ['as' => 'receipt-page', 'uses' => 'StaffController@getReceipt']);
    Route::get('/transaction/{generaldonation_id}', ['as' => 'receipt-page', 'uses' => 'StaffController@getTransaction']);
    // Route::get('/donation', ['as' => 'get-donation-page', 'uses' => 'StaffController@getDonation']);
		Route::get('/create-festive-event', ['as' => 'create-festive-event-page', 'uses' => 'StaffController@getCreateFestiveEvent']);

		Route::post('/create-festive-event', ['as' => 'add-new-festive-event-page', 'uses' => 'StaffController@postCreateFestiveEvent']);
    Route::post('/donation', ['as' => 'post-donation-page', 'uses' => 'StaffController@postDonation']);
  });

	Route::group(['prefix' => 'account'], function () {
    Route::get('/new-glaccountgroup', ['as' => 'new-glaccount-group-page', 'uses' => 'GlController@getAddNewGlAccountGroup']);
		Route::get('/edit-glaccountgroup', ['as' => 'edit-glaccount-group-page', 'uses' => 'GlController@EditGlAccountGroup']);
		Route::get('/new-glaccount', ['as' => 'new-glaccount-page', 'uses' => 'GlController@getAddNewGlAccount']);
		Route::get('/edit-glaccount', ['as' => 'edit-glaccount-page', 'uses' => 'GlController@EditGlAccount']);

		Route::post('/new-glaccountgroup', ['as' => 'post-glaccount-group-page', 'uses' => 'GlController@postAddNewGlAccountGroup']);
		Route::post('/update-glaccountgroup', ['as' => 'update-glaccount-group-page', 'uses' => 'GlController@UpdateGlAccountGroup']);
		Route::post('/new-glaccount', ['as' => 'post-glaccount-page', 'uses' => 'GlController@postAddNewGlAccount']);
		Route::post('/update-glaccount', ['as' => 'update-glaccount-page', 'uses' => 'GlController@UpdateGlAccount']);

  });

});
