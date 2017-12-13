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

Route::post('/login', ['as' => 'post-login-page', 'uses' => 'AdminController@postLogin']);

Route::group(['middleware' => 'auth'], function () {

	Route::group(['prefix' => 'admin'], function () {

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
		Route::get('/minimum-amount', ['as' => 'minimum-amount-page', 'uses' => 'AdminController@getMinimumAmount']);
		Route::get('/membership-fee', ['as' => 'membership-fee-page', 'uses' => 'AdminController@getMemebershipFee']);
		Route::get('/address-street-lists', ['as' => 'address-street-lists-page', 'uses' => 'AdminController@getAddressStreetLists']);
		Route::get('/add-address', ['as' => 'add-address-page', 'uses' => 'AdminController@getAddAddress']);
		Route::get('/address-street/edit/{id}', ['as' => 'edit-address-street-page', 'uses' => 'AdminController@getEditAddress']);
		Route::get('/address-street/delete/{id}', ['as' => 'delete-address-street-page', 'uses' => 'AdminController@deleteAddress']);

		Route::post('/search-address', ['as' => 'filter-address-page', 'uses' => 'AdminController@SearchAddress']);
		Route::post('/add-account', ['as' => 'save-account-page', 'uses' => 'AdminController@postAddAccount']);
		Route::post('/change-account', ['as' => 'change-account-page', 'uses' => 'AdminController@changeAccount']);
		Route::post('/add-dialect', ['as' => 'save-dialect-page', 'uses' => 'AdminController@postAddDialect']);
		Route::post('/update-dialect', ['as' => 'update-dialect-page', 'uses' => 'AdminController@updateDialect']);
		Route::post('/add-race', ['as' => 'save-race-page', 'uses' => 'AdminController@postAddRace']);
		Route::post('/update-race', ['as' => 'update-race-page', 'uses' => 'AdminController@updateRace']);
		Route::post('/update-acknowledge', ['as' => 'update-acknowledge-page', 'uses' => 'AdminController@postUpdateAcknowledge']);
		Route::post('/update-minimum-amount', ['as' => 'update-minimum-amount-page', 'uses' => 'AdminController@postUpdateMinimumAmount']);
		Route::post('/update-membership-fee', ['as' => 'update-membership-fee-page', 'uses' => 'AdminController@postUpdateMemebershipFee']);
		Route::post('/add-address', ['as' => 'save-address-page', 'uses' => 'AdminController@postAddAddress']);
		Route::post('/update-address', ['as' => 'update-address-page', 'uses' => 'AdminController@updateAddress']);
	});

	Route::group(['prefix' => 'operator'], function () {

		Route::get('/index', ['as' => 'main-page', 'uses' => 'OperatorController@index']);
		Route::get('/search/autocomplete', ['as' => 'search-autocomplete-page', 'uses' => 'OperatorController@getAutocomplete']);
		Route::get('/search/autocomplete2', ['as' => 'search-autocomplete2-page', 'uses' => 'OperatorController@getAutocomplete2']);
		Route::get('/search/address_street', ['as' => 'search-address-street-page', 'uses' => 'OperatorController@getAddressStreet']);
		Route::get('/search/address_postal', ['as' => 'search-address-postal-page', 'uses' => 'OperatorController@getAddressPostal']);
		Route::get('/search/populate_address_postal', ['as' => 'populate-address-postal-page', 'uses' => 'OperatorController@getPopulateAddressPostal']);
		Route::get('/search/address_translate', ['as' => 'search-address-page', 'uses' => 'OperatorController@getTranslateAddress']);
		Route::get('/address-translate', ['as' => 'address-translate-page', 'uses' => 'OperatorController@getAddressTranslate']);
		Route::get('/search-dialect', ['as' => 'search-dialect-page', 'uses' => 'OperatorController@getSearchDialect']);
		Route::get('/search-race', ['as' => 'search-race-page', 'uses' => 'OperatorController@getSearchRace']);
		Route::get('/search/check-devotee', ['as' => 'check-devotee-page', 'uses' => 'OperatorController@getCheckDevotee']);
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
		Route::get('/yuejuan-transaction-detail', ['as' => 'yuejuan-transaction-detail-page', 'uses' => 'StaffController@getYueJuanTransactionDetail']);
		Route::get('/insert-devotee', ['as' => 'insert-devotee-page', 'uses' => 'StaffController@getInsertDevotee']);
		Route::get('/donation', ['as' => 'get-donation-page', 'uses' => 'StaffController@getDonation']);
		Route::get('/receipt/{receipt_id}', ['as' => 'receipt-page', 'uses' => 'StaffController@getReceipt']);
		Route::get('/generaldonation/{generaldonation_id}', ['as' => 'receipt-detail-page', 'uses' => 'StaffController@getReceiptDetail']);
		Route::get('/transaction/{generaldonation_id}', ['as' => 'receipt-page', 'uses' => 'StaffController@getTransaction']);
		Route::get('/create-festive-event', ['as' => 'create-festive-event-page', 'uses' => 'StaffController@getCreateFestiveEvent']);
		Route::get('/print', ['as' => 'print-page', 'uses' => 'StaffController@getPrint']);
		Route::get('/reprint-generaldonation', ['as' => 'reprint-generaldonation-page', 'uses' => 'StaffController@ReprintGeneralDonation']);

		Route::post('/create-festive-event', ['as' => 'add-new-festive-event-page', 'uses' => 'StaffController@postCreateFestiveEvent']);
		Route::post('/donation', ['as' => 'post-donation-page', 'uses' => 'StaffController@postDonation']);
		Route::post('/postcijidoantion', ['as' => 'post-ciji-donation-page', 'uses' => 'StaffController@postCijiDonation']);
		Route::post('/postyuejuandoantion', ['as' => 'post-yuejuan-donation-page', 'uses' => 'StaffController@postYuejuanDonation']);
		Route::post('/samefamily-setting', ['as' => 'post-samefamily-setting-page', 'uses' => 'StaffController@postSameFamilySetting']);
		Route::post('/differentfamily-setting', ['as' => 'post-differentfamily-setting-page', 'uses' => 'StaffController@postDifferentFamilySetting']);
		Route::post('/receipt-cancellation', ['as' => 'receipt-cancellation-page', 'uses' => 'StaffController@postReceiptCancellation']);
		Route::post('/cancel-replace-transaction', ['as' => 'cancel-replace-transaction-page', 'uses' => 'StaffController@postCancelReplaceTransaction']);
		Route::post('/cancel-transaction', ['as' => 'cancel-transaction-page', 'uses' => 'StaffController@postCancelTransaction']);
		Route::post('/reprint-detail', ['as' => 'reprint-detail-page', 'uses' => 'StaffController@ReprintDetail']);
	});

	Route::group(['prefix' => 'fahui'], function () {
		//Fa Hui
		Route::get('/add-relative-and-friends', ['as' => 'add-relative-and-friends', 'uses' => 'RelativeAndFriendsController@addRelativeAndFriends']);
		Route::post('/delete-relative-and-friends', ['as' => 'delete-relative-and-friends', 'uses' => 'RelativeAndFriendsController@deleteRelativeAndFriends']);
		Route::get('/participant-list',['as' => 'fahui-participant-list', 'uses' => 'FahuiController@getParticipantList']);

		// KongDan
		Route::get('/kongdan', ['as' => 'get-kongdan-page', 'uses' => 'FahuiController@getKongDan']);
		//Route::get('/insert-devotee', ['as' => 'xiaozai-insert-devotee-page', 'uses' => 'XiaozaiController@getInsertDevotee']);

		Route::get('/insert-devotee-by-type', ['as' => 'xiaozai-insert-devotee-by-type-page', 'uses' => 'XiaozaiController@getInsertDevoteeByType']);
		Route::get('/kongdan-transaction-detail', ['as' => 'kongdan-transaction-detail-page', 'uses' => 'FahuiController@getTransactionDetail']);

		Route::post('/kongdan', ['as' => 'post-kongdan-page', 'uses' => 'FahuiController@postKongDan']);
		Route::post('/kongdan-samefamily-setting', ['as' => 'post-kongdan-samefamily-setting-page', 'uses' => 'SameFamilyCodeController@updateSfcSetting']);
		Route::post('/kongdan-differentfamily-setting', ['as' => 'post-kongdan-differentfamily-setting-page', 'uses' => 'FahuiController@postKongdanDifferentFamilySetting']);

		Route::post('/kongdan-reprint-detail', ['as' => 'kongdan-reprint-detail-page', 'uses' => 'FahuiController@ReprintDetail']);
		Route::post('/kongdan-cancel-replace-transaction', ['as' => 'kongdan-cancel-replace-transaction-page', 'uses' => 'FahuiController@postCancelReplaceTransaction']);
		Route::post('/kongdan-cancel-transaction', ['as' => 'kongdan-cancel-transaction-page', 'uses' => 'FahuiController@postCancelTransaction']);

		// QiFu
		Route::get('/qifu', ['as' => 'get-qifu-page', 'uses' => 'QiFuController@getQiFu']);
		Route::get('/qifu-transaction-detail', ['as' => 'qifu-transaction-detail-page', 'uses' => 'QiFuController@getTransactionDetail']);

		Route::post('/qifu', ['as' => 'post-qifu-page', 'uses' => 'QiFuController@postQiFu']);
		Route::post('/qifu-samefamily-setting', ['as' => 'post-qifu-samefamily-setting-page', 'uses' => 'SameFamilyCodeController@updateSfcSetting']);
		Route::post('/qifu-differentfamily-setting', ['as' => 'post-qifu-differentfamily-setting-page', 'uses' => 'QiFuController@postQifuDifferentFamilySetting']);

		Route::post('/qifu-reprint-detail', ['as' => 'qifu-reprint-detail-page', 'uses' => 'QiFuController@ReprintDetail']);
		Route::post('/qifu-cancel-replace-transaction', ['as' => 'qifu-cancel-replace-transaction-page', 'uses' => 'QiFuController@postCancelReplaceTransaction']);
		Route::post('/qifu-cancel-transaction', ['as' => 'qifu-cancel-transaction-page', 'uses' => 'QiFuController@postCancelTransaction']);

		// XiaoZai
		Route::get('/xiaozai', ['as' => 'get-xiaozai-page', 'uses' => 'XiaozaiController@getXiaoZai']);
		//Route::get('/xiaozai-transaction-detail', ['as' => 'xiaozai-transaction-detail-page', 'uses' => 'XiaoZaiController@getTransactionDetail']);
		Route::get('/transaction-detail', ['as' => 'transaction-detail-page', 'uses' => 'TransactionController@getTransactionDetail']);

		Route::post('/xiaozai', ['as' => 'post-xiaozai-page', 'uses' => 'XiaozaiController@postXiaozai']);
		Route::post('/xiaozai-samefamily-setting', ['as' => 'post-xiaozai-samefamily-setting-page', 'uses' => 'SameFamilyCodeController@updateSfcSetting']);
		Route::post('/xiaozai-differentfamily-setting', ['as' => 'post-xiaozai-differentfamily-setting-page', 'uses' => 'RelativeAndFriendsController@updateRafSetting']);

		Route::post('/xiaozai-reprint-detail', ['as' => 'xiaozai-reprint-detail-page', 'uses' => 'XiaozaiController@ReprintDetail']);
		Route::post('/xiaozai-cancel-replace-transaction', ['as' => 'kongdan-cancel-replace-transaction-page', 'uses' => 'XiaozaiController@postCancelReplaceTransaction']);
		Route::post('/xiaozai-cancel-transaction', ['as' => 'xiaozai-cancel-transaction-page', 'uses' => 'XiaozaiController@postCancelTransaction']);
	});

	Route::group(['prefix' => 'transaction'], function () {
		Route::post('/create', ['as' => 'create-transaction', 'uses' => 'TransactionController@createTransaction']);
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

	Route::group(['prefix' => 'income'], function () {
		Route::get('/income-lists', ['as' => 'income-lists-page', 'uses' => 'IncomeController@getAllIncomeLists']);
	});

	Route::group(['prefix' => 'expenditure'], function () {
		// Route::get('/manage-expenditure', ['as' => 'manage-expenditure-page', 'uses' => 'ExpenditureController@getManageExpenditure']);
		// Route::get('/expenditure-detail', ['as' => 'expenditure-detail-page', 'uses' => 'ExpenditureController@getExpenditureDetail']);
		Route::get('/search/supplier', ['as' => 'search-supplier-page', 'uses' => 'ExpenditureController@getSearchSupplier']);
		// Route::get('/delete/{id}', ['as' => 'delete-expenditure-page', 'uses' => 'ExpenditureController@deleteExpenditure']);
		//
		// Route::post('/new-expenditure', ['as' => 'new-expenditure-page', 'uses' => 'ExpenditureController@postAddNewExpenditure']);
		// Route::post('/update-expenditure', ['as' => 'update-expenditure-page', 'uses' => 'ExpenditureController@postUpdateExpenditure']);
	});

	Route::group(['prefix' => 'vendor'], function () {
		Route::get('/manage-ap-vendor', ['as' => 'manage-ap-vendor-page', 'uses' => 'VendorController@getManageVendor']);
		Route::get('/vendor-detail', ['as' => 'vendor-detail-page', 'uses' => 'VendorController@getVendorDetail']);
		Route::get('/delete/{id}', ['as' => 'delete-vendor-page', 'uses' => 'VendorController@deleteVendor']);

		Route::post('/new-vendor', ['as' => 'new-vendor-page', 'uses' => 'VendorController@postAddNewVendor']);
		Route::post('/update-vendor', ['as' => 'update-vendor-page', 'uses' => 'VendorController@postUpdateVendor']);
	});

	Route::group(['prefix' => 'journal'], function () {
		Route::get('/manage-journal', ['as' => 'manage-journal-page', 'uses' => 'JournalController@getManageJournal']);
		Route::get('/journal-detail', ['as' => 'journal-detail-page', 'uses' => 'JournalController@getJournalDetail']);
		Route::get('/delete/{id}', ['as' => 'journalentry-delete-page', 'uses' => 'JournalController@getDeleteJournalEntry']);

		Route::post('/new-journal', ['as' => 'new-journal-page', 'uses' => 'JournalController@postAddNewJournal']);
	});

	Route::group(['prefix' => 'journalentry'], function () {
		Route::get('/manage-journalentry', ['as' => 'manage-journalentry-page', 'uses' => 'JournalEntryController@getManageJournalEntry']);
		Route::get('/journalentry-detail', ['as' => 'journalentry-detail-page', 'uses' => 'JournalEntryController@getJournalEntryDetail']);
		Route::get('/delete/{id}', ['as' => 'journalentry-delete-page', 'uses' => 'JournalEntryController@getDeleteJournalEntry']);

		Route::post('/new-journalentry', ['as' => 'new-journalentry-page', 'uses' => 'JournalEntryController@postAddNewJournalentry']);
	});

	Route::group(['prefix' => 'payment'], function () {
		Route::get('/manage-payment', ['as' => 'manage-payment-page', 'uses' => 'PaymentController@getManagePayment']);
		Route::get('/get-bank-name', ['as' => 'get-bank-name-page', 'uses' => 'PaymentController@getBankName']);
		Route::get('/payment-voucher-detail', ['as' => 'payment-voucher-detail-page', 'uses' => 'PaymentController@getPaymentVoucherDetail']);

		Route::post('/new-payment', ['as' => 'new-payment-page', 'uses' => 'PaymentController@postAddNewPayment']);
	});

	Route::group(['prefix' => 'pettycash'], function () {
		Route::get('/manage-pettycash', ['as' => 'manage-pettycash-page', 'uses' => 'PettyCashController@getManagePettyCash']);
		Route::get('/supplier-name', ['as' => 'suppiler-name-page', 'uses' => 'PettyCashController@getSupplierName']);
		Route::get('/pettycash-voucher-detail', ['as' => 'pettycash-voucher-detail-page', 'uses' => 'PettyCashController@getPettyCashVoucherDetail']);

		Route::post('/new-pettycash', ['as' => 'new-pettycash-page', 'uses' => 'PettyCashController@postAddNewPettyCash']);
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
		Route::get('/trialbalance-report', ['as' => 'trialbalance-report-page', 'uses' => 'ReportController@getTrialBalanceReport']);
		Route::get('/cashflow-report', ['as' => 'cashflow-report-page', 'uses' => 'ReportController@getCashflowReport']);
		Route::get('/summary-settlement-report', ['as' => 'summary-settlement-report-page', 'uses' => 'ReportController@getSummarySettlementReport']);
		Route::get('/settlement-report', ['as' => 'settlement-report-page', 'uses' => 'ReportController@getSettlementReport']);

		Route::post('/report-detail', ['as' => 'report-detail-page', 'uses' => 'ReportController@getReportDetail']);
		Route::post('/cashflow-report-detail', ['as' => 'cashflow-report-detail-page', 'uses' => 'ReportController@getCashflowReportDetail']);
		Route::post('/trialbalance-report-detail', ['as' => 'trialbalance-report-detail-page', 'uses' => 'ReportController@getTrialBalanceReportDetail']);
		Route::post('/summary-settlement-report', ['as' => 'summary-settlement-report-page', 'uses' => 'ReportController@postSummarySettlementReport']);
		Route::post('/settlement-report', ['as' => 'settlement-report-page', 'uses' => 'ReportController@postSettlementReport']);
	});

});
