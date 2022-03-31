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

Route::get('/', function () {
    return redirect('login');
});
Route::get('/test', function () {
    return view('pusherManagement/showNotification');
});


Route::post('/postMessage', 'SendMessageController@sendMessage')->name('postMessage');

Auth::routes();
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['auth']], function(){
        Route::get('/home', 'HomeController@index')->name('home');
        
        Route::get('/chats', 'chatController@index')->name('chat.index');
        Route::post('/read_all_messages', 'SendMessageController@readAll')->name('readAll');

        Route::resource('claim', 'ClaimController');
        //GOP
        Route::get('{claim_type}/claim', 'ClaimController@index')->name('claimGOP.index');
        Route::get('{claim_type}/claim/create', 'ClaimController@create')->name('claimGOP.create');
        Route::get('{claim_type}/claim/{claim}/edit', 'ClaimController@edit')->name('claimGOP.edit');

        Route::post('/claim/uploadSortedFile/{id}', 'ClaimController@uploadSortedFile')->name('uploadSortedFile');
        Route::post('/claim/uploadSortedFileGOP/{id}', 'ClaimController@uploadSortedFileGOP')->name('uploadSortedFileGOP');
        Route::post('/claim/sendSortedFile/{id}', 'ClaimController@sendSortedFile')->name('claim.sendSortedFile');
        Route::post('/claim/setPcvExpense/{id}', 'ClaimController@setPcvExpense')->name('claim.setPcvExpense');
        Route::post('/claim/sendPayment/{id}', 'ClaimController@sendPayment')->name('claim.sendPayment');
        Route::post('/claim/setDebt/{id}', 'ClaimController@setDebt')->name('claim.setDebt');
        Route::post('/claim/payDebt/{id}', 'ClaimController@payDebt')->name('claim.payDebt');
        Route::post('/claim/setProvGOPPresAmt/{id}', 'ClaimController@setProvGOPPresAmt')->name('claim.setProvGOPPresAmt');
        Route::post('/claim/requestManagerGOP/{id}', 'ClaimController@requestManagerGOP')->name('claim.requestManagerGOP');
        Route::post('/claim/sendMailProvider', 'ClaimController@sendMailProvider')->name('claim.sendMailProvider');
        Route::post('/claim/sendCSRFile/{id}', 'ClaimController@sendCSRFile')->name('claim.sendCSRFile');
        Route::post('/claim/deletePage/{id}', 'ClaimController@deletePage')->name('claim.deletePage');
        Route::post('/claim/downloadFinishFile/{id}', 'ClaimController@downloadFinishFile')->name('claim.downloadFinishFile');
        Route::post('/claim/confirmContract', 'ClaimController@confirmContract')->name('claim.confirmContract');
        Route::post('/claim/setJetcase/{id}', 'ClaimController@setJetcase')->name('claim.setJetcase');
        Route::post('/claim/setAdminFee/{id}', 'ClaimController@setAdminFee')->name('claim.setAdminFee');
        Route::post('/claim/update_invoice/{id}', 'ClaimController@update_invoice')->name('claim.update_invoice');
        Route::post('/claim/sendNoticationMobile/{id}', 'ClaimController@sendNoticationMobile')->name('claim.sendNoticationMobile');
        Route::post('/claim/sendMailCustomer', 'ClaimController@sendMailCustomer')->name('claim.sendMailCustomer');
        Route::get('/claimExport', 'ClaimController@claimExport')->name('claim.claimExport');
        
        Route::get('/claim/barcode/{barcode}', 'ClaimController@barcode_link')->name('claim.barcode_link');

        Route::post('/search', 'ClaimController@searchFullText')->name('search');
        Route::post('/search2', 'ClaimController@searchFullText2')->name('search2');
        Route::post('/template', 'ClaimController@template')->name('template');
        Route::post('/requestLetter','ClaimController@requestLetter')->name('requestLetter');
        Route::post('/exportLetter','ClaimController@exportLetter')->name('exportLetter');
        Route::post('/exportLetterPDF','ClaimController@exportLetterPDF')->name('exportLetterPDF');
        Route::post('/previewLetter','ClaimController@previewLetter')->name('previewLetter');
        Route::post('/changeStatus','ClaimController@changeStatus')->name('changeStatus');
        Route::post('/waitCheck','ClaimController@waitCheck')->name('waitCheck');
        Route::post('/sendEtalk','ClaimController@sendEtalk')->name('sendEtalk');
        Route::post('/changeStatusEtalk','ClaimController@changeStatusEtalk')->name('changeStatusEtalk');
        Route::get('/sendSummaryEtalk/{id}',  'ClaimController@sendSummaryEtalk')->name('sendSummaryEtalk');
        Route::post('/attachEmail/{id}',  'ClaimController@attachEmail')->name('attachEmail');
        Route::post('/custommerConfirm/{id}',  'ClaimController@custommerConfirm')->name('custommerConfirm');

        
        Route::post('/addNote','ClaimController@addNote')->name('addNote');
        Route::get('/test/{claim_id}', 'ClaimController@test')->name('test.index');

        Route::resource('reason_reject', 'ReasonRejectController');
        Route::resource('product', 'ProductController');
        Route::resource('term', 'TermController');
        Route::resource('letter_template', 'LetterTemplateController');

        Route::get('importExportView', 'CSVController@importExportView')->middleware(['role:Admin']);
        Route::post('import', 'CSVController@import')->name('import')->middleware(['role:Admin']);
        Route::resource('admins', 'AdminController')->middleware(['role:Admin']);
        Route::resource('role', 'PermissionController')->middleware(['role:Admin']);
        Route::resource('permiss', 'PermissController')->middleware(['role:Admin']);

        Route::resource('roleChangeStatuses', 'RoleChangeStatusController')->middleware(['role:Admin']);
        Route::resource('levelRoleStatuses', 'LevelRoleStatusController')->middleware(['role:Admin']);
        Route::resource('transactionRoleStatuses', 'TransactionRoleStatusController')->middleware(['role:Admin']);

        Route::resource('message', 'SendMessageController');
        Route::post('message/destroyMany', 'SendMessageController@destroyMany')->name('message.destroyMany');
        Route::get('/sent','SendMessageController@sent')->name('message.sent');
        Route::get('/trash','SendMessageController@trash')->name('message.trash');
        Route::post('message/important', 'SendMessageController@important')->name('message.important');


        //ajax
        Route::get('/dataAjaxHBSClaim', 'AjaxCommonController@dataAjaxHBSClaim')->name('dataAjaxHBSClaim');
        Route::post('/loadInfoAjaxHBSClaim', 'AjaxCommonController@loadInfoAjaxHBSClaim')->name('loadInfoAjaxHBSClaim');
        Route::get('/dataAjaxHBSProv', 'AjaxCommonController@dataAjaxHBSProv')->name('dataAjaxHBSProv');
        Route::get('/dataAjaxHBSGOPClaim', 'AjaxCommonController@dataAjaxHBSGOPClaim')->name('dataAjaxHBSGOPClaim');
        Route::get('/dataAjaxHBSProvByClaim/{claim_oid}', 'AjaxCommonController@dataAjaxHBSProvByClaim')->name('dataAjaxHBSProvByClaim');
        Route::get('/dataAjaxHBSDiagnosis', 'AjaxCommonController@dataAjaxHBSDiagnosis')->name('dataAjaxHBSDiagnosis');
        Route::post('/renderEmailProv', 'AjaxCommonController@renderEmailProv')->name('renderEmailProv');
        

        Route::get('/dataAjaxHBSClaimRB', 'AjaxCommonController@dataAjaxHBSClaimRB')->name('dataAjaxHBSClaimRB');
        Route::post('/loadInfoAjaxHBSClaimRB', 'AjaxCommonController@loadInfoAjaxHBSClaimRB')->name('loadInfoAjaxHBSClaimRB');
        Route::post('/checkRoomBoard', 'AjaxCommonController@checkRoomBoard')->name('checkRoomBoard');

        Route::resource('roomAndBoards', 'RoomAndBoardController');

        Route::get('users/',  'UserController@edit')->name('MyProfile');
        Route::post('users/update','UserController@update');

        Route::get('getPaymentHistory/{cl_no}',  'AjaxCommonController@getPaymentHistory')->name('getPaymentHistory');
        Route::get('getPaymentHistoryCPS/{cl_no}',  'AjaxCommonController@getPaymentHistoryCPS')->name('getPaymentHistoryCPS');
        Route::get('getBalanceCPS/{mem_ref_no}/{cl_no}',  'AjaxCommonController@getBalanceCPS')->name('getBalanceCPS');
        

        Route::resource('claimWordSheets', 'ClaimWordSheetController');
        Route::get('claimWordSheets/pdf/{claimWordSheet}',  'ClaimWordSheetController@pdf')->name('claimWordSheets.pdf');
        Route::get('claimWordSheets/summary/{claimWordSheet}',  'ClaimWordSheetController@summary')->name('claimWordSheets.summary');
        Route::get('claimWordSheets/sendSortedFile/{claimWordSheet}',  'ClaimWordSheetController@sendSortedFile')->name('claimWordSheets.sendSortedFile');
        Route::get('/sendMfile/{claim_id}', 'AjaxCommonController@sendMfile')->name('sendMfile');
        Route::get('/viewMfile/{mfile_claim_id}/{mfile_claim_file_id}', 'AjaxCommonController@viewMfile')->name('viewMfile');
        
        //setting
        Route::get('setting/',  'SettingController@index')->name('setting.index')->middleware(['role:Admin']);
        Route::post('setting/update','SettingController@update')->middleware(['role:Admin']);
        Route::post('setting/notifiAllUser','SettingController@notifiAllUser')->middleware(['role:Admin']);
        Route::post('setting/checkUpdateClaim','SettingController@checkUpdateClaim')->middleware(['role:Admin']);
        Route::post('setting/checkUpdateLogApproved','SettingController@checkUpdateLogApproved')->middleware(['role:Admin']);
        Route::post('setting/updateBenhead','SettingController@updateBenhead')->middleware(['role:Admin']);
        Route::post('setting/getMessageMail','SettingController@getMessageMail')->middleware(['role:Admin']);
        
        Route::resource('uncSign', 'UncSignController');

        //payment Histor
        Route::resource('PaymentHistory', 'PaymentHistoryController');
        Route::get('get_renewed_claim',  'PaymentHistoryController@get_renewed_claim');

        //provider
        Route::resource('providers', 'ProviderController');

        //Group mem
        Route::resource('groupUsers', 'GroupUserController');

        //report for admin claim
        Route::resource('reportAdmins', 'ReportAdminController');

        //report for admin claim
        Route::resource('reportGop', 'ReportGopController');

        //Mail box
        Route::resource('mailbox', 'MailBoxController');
        Route::get('mailbox/{id_mess}/download/{id}','MailBoxController@download');
        Route::get('mailbox-error-messages','MailBoxController@error_messages');
        
        // pocy 
        Route::resource('pocy', 'PocyManagementController');
    });

});

Auth::routes();

Route::get('generator_builder', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder')->name('io_generator_builder');

Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate')->name('io_field_template');

Route::get('relation_field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@relationFieldTemplate')->name('io_relation_field_template');

Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate')->name('io_generator_builder_generate');

Route::post('generator_builder/rollback', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@rollback')->name('io_generator_builder_rollback');

Route::post(
    'generator_builder/generate-from-file',
    '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generateFromFile'
)->name('io_generator_builder_generate_from_file');


// Push Subscriptions
Route::post('check_subscriptions', 'PushController@check_subscriptions');
Route::post('subscriptions', 'PushController@update');
Route::post('subscriptions/delete', 'PushController@destroy');

