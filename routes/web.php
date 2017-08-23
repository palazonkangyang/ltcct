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
		Route::get('/prelogin-note', ['as' => 'prelogin-note-page', 'uses' => 'AdminController@getPreLoginNote']);
		Route::get('/dashboard', ['as' => 'dashboard-page', 'uses' => 'AdminController@dashboard']);
    Route::get('/add-account', ['as' => 'add-account-page', 'uses' => 'AdminController@getAddAccount']);
		Route::get('/all-accounts', ['as' => 'all-accounts-page', 'uses' => 'AdminController@getAllAccounts']);
		Route::get('/account/edit/{id}', ['as' => 'all-account-page', 'uses' => 'AdminController@getEditAccount']);
		Route::get('/account/delete/{id}', ['as' => 'all-account-page', 'uses' => 'AdminController@deleteAccount']);
		Route::get('/add-dialect', ['as' => 'add-dialect-page', 'uses' => 'AdminController@getAddDialect']);
		Route::get('/all-dialects', ['as' => 'all-dialects-page', 'uses' => 'AdminController@getAllDialects']);
		Route::get('/dialect/edit/{id}', ['as' => 'edit-dialect-page', 'uses' => 'AdminController@getEditDialect']);
		Route::get('/dialect/delete/{id}', ['as' => 'delete-dialect-page', 'uses' => 'AdminController@deleteDialect']);
		Route::get('/add-race', ['as' => 'add-race-page', 'uses' => 'AdminController@getAddRace']);
		Route::get('/all-race', ['as' => 'all-race-page', 'uses' => 'AdminController@getAllRace']);
		Route::get('/race/edit/{id}', ['as' => 'edit-race-page', 'uses' => 'AdminController@getEditRace']);
		Route::get('/race/delete/{id}', ['as' => 'delete-race-page', 'uses' => 'AdminController@deleteRace']);

		Route::post('/add-account', ['as' => 'save-account-page', 'uses' => 'AdminController@postAddAccount']);
		Route::post('/change-account', ['as' => 'change-account-page', 'uses' => 'AdminController@changeAccount']);
		Route::post('/add-dialect', ['as' => 'save-dialect-page', 'uses' => 'AdminController@postAddDialect']);
		Route::post('/update-dialect', ['as' => 'update-dialect-page', 'uses' => 'AdminController@updateDialect']);
		Route::post('/add-race', ['as' => 'save-race-page', 'uses' => 'AdminController@postAddRace']);
		Route::post('/update-race', ['as' => 'update-race-page', 'uses' => 'AdminController@updateRace']);
		Route::post('/update-acknowledge', ['as' => 'update-acknowledge-page', 'uses' => 'AdminController@postUpdateAcknowledge']);
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
		Route::get('/devotee/getMemberDetail', ['as' => 'get-member-page', 'uses' => 'OperatorController@getMemberDetail']);
		Route::get('/getFocusDevoteeDetail', ['as' => 'get-focus-devotee-detail-page', 'uses' => 'OperatorController@getFocusDevoteeDetail']);
		Route::get('/devotee/focus-devotee', ['as' => 'get-json-focus-devotee-page', 'uses' => 'OperatorController@getJSONFocusDevotee']);
		Route::get('/devotee/delete/{devotee_id}', ['as' => 'delete-devotee-page', 'uses' => 'OperatorController@deleteDevotee']);
		Route::get('/devotee/{devotee_id}', ['as' => 'devotee-page', 'uses' => 'OperatorController@getDevoteeByID']);

		Route::match(["post", "get"], '/focus-devotee', ['as' => 'focus-devotee-page', 'uses' => 'OperatorController@getFocusDevotee']);
		Route::post('/relocation', ['as' => 'relocation-devotee-page', 'uses' => 'OperatorController@postRelocationDevotees']);
		Route::post('/new-devotee', ['as' => 'new-devotee-page', 'uses' => 'OperatorController@postAddDevotee']);
		Route::post('/edit-devotee', ['as' => 'edit-devotee-page', 'uses' => 'OperatorController@postEditDevotee']);
  });

  Route::group(['prefix' => 'staff'], function () {
    Route::get('/search-devotee', ['as' => 'search-devotee-page', 'uses' => 'StaffController@getSearchDevotee']);
		Route::get('/search-devotee-id', ['as' => 'search-devotee-id-page', 'uses' => 'StaffController@getSearchDevoteeID']);
		Route::get('/transaction-detail', ['as' => 'transaction-detail-page', 'uses' => 'StaffController@getTransactionDetail']);
		Route::get('/insert-devotee', ['as' => 'insert-devotee-page', 'uses' => 'StaffController@getInsertDevotee']);
    Route::get('/donation', ['as' => 'get-donation-page', 'uses' => 'StaffController@getDonation']);
    Route::get('/receipt/{receipt_id}', ['as' => 'receipt-page', 'uses' => 'StaffController@getReceipt']);
		Route::get('/generaldonation/{generaldonation_id}', ['as' => 'receipt-detail-page', 'uses' => 'StaffController@getReceiptDetail']);
    Route::get('/transaction/{generaldonation_id}', ['as' => 'receipt-page', 'uses' => 'StaffController@getTransaction']);
		Route::get('/create-festive-event', ['as' => 'create-festive-event-page', 'uses' => 'StaffController@getCreateFestiveEvent']);
		Route::get('/print', ['as' => 'print-page', 'uses' => 'StaffController@getPrint']);

		Route::post('/create-festive-event', ['as' => 'add-new-festive-event-page', 'uses' => 'StaffController@postCreateFestiveEvent']);
    Route::post('/donation', ['as' => 'post-donation-page', 'uses' => 'StaffController@postDonation']);
		Route::post('/samefamily-setting', ['as' => 'post-samefamily-setting-page', 'uses' => 'StaffController@postSameFamilySetting']);
		Route::post('/differentfamily-setting', ['as' => 'post-differentfamily-setting-page', 'uses' => 'StaffController@postDifferentFamilySetting']);
		Route::post('/receipt-cancellation', ['as' => 'receipt-cancellation-page', 'uses' => 'StaffController@postReceiptCancellation']);
		Route::post('/cancel-replace-transaction', ['as' => 'cancel-replace-transaction-page', 'uses' => 'StaffController@postCancelReplaceTransaction']);
		Route::post('/reprint-detail', ['as' => 'reprint-detail-page', 'uses' => 'StaffController@ReprintDetail']);
  });

	Route::group(['prefix' => 'account'], function () {
    Route::get('/new-glaccountgroup', ['as' => 'new-glaccount-group-page', 'uses' => 'GlController@getAddNewGlAccountGroup']);
		Route::get('/edit-glaccountgroup', ['as' => 'edit-glaccount-group-page', 'uses' => 'GlController@EditGlAccountGroup']);
		Route::get('/new-glaccount', ['as' => 'new-glaccount-page', 'uses' => 'GlController@getAddNewGlAccount']);
		Route::get('/edit-glaccount', ['as' => 'edit-glaccount-page', 'uses' => 'GlController@EditGlAccount']);
		Route::get('/chart-all-accounts', ['as' => 'chart-all-accounts-page', 'uses' => 'GlController@getChartAllAccounts']);
		Route::get('/glcodegroup-detail', ['as' => 'glcodegroup-detail-page', 'uses' => 'GlController@getGlCodeGroupDetail']);
		Route::get('/glcode-detail', ['as' => 'glcode-detail-page', 'uses' => 'GlController@getGlCodeDetail']);

		Route::post('/new-glaccountgroup', ['as' => 'post-glaccount-group-page', 'uses' => 'GlController@postAddNewGlAccountGroup']);
		Route::post('/update-glaccountgroup', ['as' => 'update-glaccount-group-page', 'uses' => 'GlController@UpdateGlAccountGroup']);
		Route::post('/new-glaccount', ['as' => 'post-glaccount-page', 'uses' => 'GlController@postAddNewGlAccount']);
		Route::post('/update-glaccount', ['as' => 'update-glaccount-page', 'uses' => 'GlController@UpdateGlAccount']);

  });

	Route::group(['prefix' => 'expenditure'], function () {
		Route::get('/manage-expenditure', ['as' => 'manage-expenditure-page', 'uses' => 'ExpenditureController@getManageExpenditure']);
		Route::get('/expenditure-detail', ['as' => 'expenditure-detail-page', 'uses' => 'ExpenditureController@getExpenditureDetail']);

		Route::post('/new-expenditure', ['as' => 'new-expenditure-page', 'uses' => 'ExpenditureController@postAddNewExpenditure']);
		Route::post('/update-expenditure', ['as' => 'update-expenditure-page', 'uses' => 'ExpenditureController@postUpdateExpenditure']);
	});

	Route::group(['prefix' => 'journalentry'], function () {
		Route::get('/manage-journalentry', ['as' => 'manage-journalentry-page', 'uses' => 'JournalEntryController@getManageJournalEntry']);
		Route::get('/journalentry-detail', ['as' => 'journalentry-detail-page', 'uses' => 'JournalEntryController@getJournalEntryDetail']);

		Route::post('/new-journalentry', ['as' => 'new-journalentry-page', 'uses' => 'JournalEntryController@postAddNewJournalentry']);
		Route::post('/update-journalentry', ['as' => 'update-journalentry-page', 'uses' => 'JournalEntryController@postUpdateJournalentry']);
	});

	Route::group(['prefix' => 'paid'], function () {
		Route::get('/manage-paid', ['as' => 'manage-paid-page', 'uses' => 'PaidController@getManagePaid']);
		Route::get('/paid-detail', ['as' => 'paid-detail-page', 'uses' => 'PaidController@getPaidDetail']);

		Route::post('/new-paid', ['as' => 'new-paid-page', 'uses' => 'PaidController@postAddNewPaid']);
		Route::post('/update-paid', ['as' => 'update-paid-page', 'uses' => 'PaidController@postUpdatePaid']);
	});


	Route::group(['prefix' => 'job'], function () {
    Route::get('/manage-job', ['as' => 'manage-job-page', 'uses' => 'JobController@getJob']);
		Route::get('/get-joblists', ['as' => 'get-joblists-page', 'uses' => 'JobController@getJobLists']);
		Route::get('/job-detail', ['as' => 'job-detail-page', 'uses' => 'JobController@getJobDetail']);
		Route::get('/delete/{id}', ['as' => 'delete-job-page', 'uses' => 'JobController@deleteJob']);

		Route::post('/new-job', ['as' => 'new-job-page', 'uses' => 'JobController@postAddNewJob']);
		Route::post('/update-job', ['as' => 'update-page', 'uses' => 'JobController@postUpdateJob']);
  });

	Route::group(['prefix' => 'report'], function () {
    Route::get('/income-report', ['as' => 'income-report-page', 'uses' => 'ReportController@getIncomeReport']);

		Route::get('/report-detail', ['as' => 'report-detail-page', 'uses' => 'ReportController@getReportDetail']);

  });

});
