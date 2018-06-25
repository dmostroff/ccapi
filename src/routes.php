<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('middleware.php');

//$logger = $app->getContainer()['logger'] ;
//$router = $app->getContainer()['router'] ;
//$view = $app->getContainer()['view'];
if(true) {
 // ...allow all CORS preflight requests
 $app->options('/{routes:.+}', function($req, $resp, $args) use($app) {
    return $resp ;
 }) ;
 $app->add(function ($req, $res, $next) {
   $mw = new middleware();
   $response = $mw->__invoke($req, $res, $next);
//   $response = $next($req, $res);
   return $response
           ->withHeader('Access-Control-Allow-Origin', '*')
           ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
           ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
 });
}
/*-------------------------------------------------------*
 *--  testing
 *-------------------------------------------------------*/
//$app->post('/test/postman'                                         , new LazyLoad($app,"", 'Test_PostHandler'))->setName('testPostHandler') ;
//$app->get ('/test/{str}'                                           , new LazyLoad($app,"", 'Test_Handler'))->setName('testHandler') ;
//
$app->get('/test', function ($request, $response) {
    return 'test carg';
    
});
$app->get('/test/dbconn', new FileLoad( $app, "", 'Test_dbconntest'))->setName('testDBConnTest');

/* ------------------------------------------------------------
 * Adm 
 ------------------------------------------------------------*/
$app->get('/admin/settings', new FileLoad($app, '', 'Adm_SettingsGetAll'))->setName('AdmSettingsGetAll');
$app->get('/admin/settings/{prefix}/{keyname}', new FileLoad($app, '', 'Adm_SettingsGet'))->setName('AdmSettingsGet');
$app->post('/admin/settings', new FileLoad($app, '', 'Adm_SettingsPost'))->setName('AdmSettingsPost');
$app->delete('/admin/settings', new FileLoad($app, '', 'Adm_SettingsDelete'))->setName('AdmSettingsDelete');

$app->get('/admin/tags', new FileLoad( $app, "", 'Adm_TagsGetAll'))->setName('admTagsGetAll');
$app->get('/admin/tags/{prefix}', new FileLoad( $app, "", 'Adm_TagsGet'))->setName('admTagsGet');
$app->get('/admin/tags/{prefix}/{tag}', new FileLoad( $app, "", 'Adm_TagGet'))->setName('admTagGet');
$app->post('/admin/tags', new FileLoad( $app, "", 'Adm_TagPost'))->setName('admTagPost');
$app->delete('/admin/tags', new FileLoad( $app, "", 'Adm_TagDelete'))->setName('admTagDelete');

$app->get('/admin/users', new FileLoad($app, '', 'Adm_UsersGetAll'))->setName('AdmUsersGetAll');
$app->get('/admin/users/{user_id}', new FileLoad($app, '', 'Adm_UsersGet'))->setName('AdmUsersGet');
$app->post('/admin/users', new FileLoad($app, '', 'Adm_UsersPost'))->setName('AdmUsersPost');
$app->post('/admin/users/login', new FileLoad($app, '', 'Adm_UsersLogin'))->setName('AdmUsersLogin');
$app->post('/admin/users/logout', new FileLoad($app, '', 'Adm_UsersLogout'))->setName('AdmUsersLogout');
$app->delete('/admin/users', new FileLoad($app, '', 'Adm_UsersDelete'))->setName('AdmUsersDelete');

/* ------------------------------------------------------------
 * Account
 ------------------------------------------------------------*/
$app->get('/account', new FileLoad($app, '', 'Account_AccountGetAll'))->setName('AccountGetAll');
$app->get('/account/person/{client_id:\d+}', new FileLoad($app, '', 'Account_AccountPersonGet'))->setName('AccountPersonGet');
$app->get('/account/{account_id:\d+}', new FileLoad($app, '', 'Account_AccountGet'))->setName('AccountGet');
$app->post('/account', new FileLoad($app, '', 'Account_AccountPost'))->setName('AccountPost');
$app->delete('/account', new FileLoad($app, '', 'Account_AccountDelete'))->setName('AccountDelete');

$app->get('/account/baltransferinfo', new FileLoad($app, '', 'Account_BaltransferinfoGetAll'))->setName('AccountBaltransferinfoGetAll');
$app->get('/account/baltransferinfo/{bal_id}', new FileLoad($app, '', 'Account_BaltransferinfoGet'))->setName('AccountBaltransferinfoGet');
$app->post('/account/baltransferinfo', new FileLoad($app, '', 'Account_BaltransferinfoPost'))->setName('AccountBaltransferinfoPost');
$app->delete('/account/baltransferinfo', new FileLoad($app, '', 'Account_BaltransferinfoDelete'))->setName('AccountBaltransferinfoDelete');

$app->get('/account/cc/actions', new FileLoad($app, '', 'Account_CcActionsGetAll'))->setName('ClientCcActionsGetAll');
$app->get('/account/cc/actions/{ccaction_id}', new FileLoad($app, '', 'Account_CcActionsGet'))->setName('ClientCcActionsGet');
$app->post('/account/cc/actions', new FileLoad($app, '', 'Account_CcActionsPost'))->setName('ClientCcActionsPost');
$app->delete('/account/cc/actions', new FileLoad($app, '', 'Account_CcActionsDelete'))->setName('ClientCcActionsDelete');

$app->get('/account/cc/transactions', new FileLoad($app, '', 'Account_TransactionsGetAll'))->setName('AccountTransactionsGetAll');
$app->get('/account/cc/transactions/{cctrans_id}', new FileLoad($app, '', 'Account_TransactionsGet'))->setName('AccountTransactionsGet');
$app->post('/account/cc/transactions', new FileLoad($app, '', 'Account_TransactionsPost'))->setName('Account_TransactionsPost');
$app->delete('/account/cc/transactions', new FileLoad($app, '', 'Account_TransactionsDelete'))->setName('Account_TransactionsDelete');


/* ------------------------------------------------------------
 * Cc
 ------------------------------------------------------------*/
$app->get('/cc/cards', new FileLoad($app, '', 'Cc_CardsGetAll'))->setName('CcCardsGetAll');
$app->get('/cc/cards/{cc_card_id}', new FileLoad($app, '', 'Cc_CardsGet'))->setName('CcCardsGet');
$app->post('/cc/cards', new FileLoad($app, '', 'Cc_CardsPost'))->setName('CcCardsPost');
$app->delete('/cc/cards', new FileLoad($app, '', 'Cc_CardsDelete'))->setName('CcCardsDelete');

$app->get('/cc/company', new FileLoad($app, '', 'Cc_CompanyGetAll'))->setName('CcCompanyGetAll');
$app->get('/cc/company/{cc_company_id}', new FileLoad($app, '', 'Cc_CompanyGet'))->setName('CcCompanyGet');
$app->get('/cc/company/detail/{cc_company_id}', new FileLoad($app, '', 'Cc_CompanyGetDetail'))->setName('CcCompanyGetDetail');
$app->post('/cc/company', new FileLoad($app, '', 'Cc_CompanyPost'))->setName('CcCompanyPost');
$app->delete('/cc/company', new FileLoad($app, '', 'Cc_CompanyDelete'))->setName('CcCompanyDelete');

$app->get('/cc/company/cards/{cc_company_id}', new FileLoad($app, '', 'Cc_CompanyCards'))->setName('CcCompanyCards');

/* ------------------------------------------------------------
 * Client
 ------------------------------------------------------------*/
$app->get('/client/address', new FileLoad($app, '', 'Client_AddressGetAll'))->setName('ClientAddressGetAll');
$app->get('/client/address/{address_id}', new FileLoad($app, '', 'Client_AddressGet'))->setName('ClientAddressGet');
$app->get('/client/person/{client_id}/address', new FileLoad($app, '', 'Client_AddressPersonGetAll'))->setName('ClientAddressPersonGetAll');
$app->post('/client/address', new FileLoad($app, '', 'Client_AddressPost'))->setName('ClientAddressPost');
$app->delete('/client/address', new FileLoad($app, '', 'Client_AddressDelete'))->setName('ClientAddressDelete');

$app->get('/client/business', new FileLoad($app, '', 'Client_BusinessGetAll'))->setName('ClientBusinessGetAll');
$app->get('/client/business/{pbiz_id}', new FileLoad($app, '', 'Client_BusinessGet'))->setName('ClientBusinessGet');
$app->post('/client/business', new FileLoad($app, '', 'Client_BusinessPost'))->setName('ClientBusinessPost');
$app->delete('/client/business', new FileLoad($app, '', 'Client_BusinessDelete'))->setName('ClientBusinessDelete');

$app->get('/client/cc', new FileLoad($app, '', 'Client_CcGetAll'))->setName('ClientCcGetAll');
$app->get('/client/cc/{clicc_id}', new FileLoad($app, '', 'Client_CcGet'))->setName('ClientCcGet');
$app->post('/client/cc', new FileLoad($app, '', 'Client_CcPost'))->setName('ClientCcPost');
$app->delete('/client/cc', new FileLoad($app, '', 'Client_CcDelete'))->setName('ClientCcDelete');

$app->get('/client/cchistory', new FileLoad($app, '', 'Client_CchistoryGetAll'))->setName('ClientCchistoryGetAll');
$app->get('/client/cchistory/{cchist_id}', new FileLoad($app, '', 'Client_CchistoryGet'))->setName('ClientCchistoryGet');
$app->post('/client/cchistory', new FileLoad($app, '', 'Client_CchistoryPost'))->setName('ClientCchistoryPost');
$app->delete('/client/cchistory', new FileLoad($app, '', 'Client_CchistoryDelete'))->setName('ClientCchistoryDelete');

$app->get('/client/financials', new FileLoad($app, '', 'Client_FinancialsGetAll'))->setName('ClientFinancialsGetAll');
$app->get('/client/financials/{financial_id}', new FileLoad($app, '', 'Client_FinancialsGet'))->setName('ClientFinancialsGet');
$app->post('/client/financials', new FileLoad($app, '', 'Client_FinancialsPost'))->setName('ClientFinancialsPost');
$app->delete('/client/financials', new FileLoad($app, '', 'Client_FinancialsDelete'))->setName('ClientFinancialsDelete');

$app->get('/client/person', new FileLoad($app, '', 'Client_PersonGetAll'))->setName('ClientPersonGetAll');
$app->get('/client/person/{client_id:\d+}', new FileLoad($app, '', 'Client_PersonGet'))->setName('ClientPersonGet');
$app->post('/client/person', new FileLoad($app, '', 'Client_PersonPost'))->setName('ClientPersonPost');
$app->delete('/client/person', new FileLoad($app, '', 'Client_PersonDelete'))->setName('ClientPersonDelete');

$app->get('/client/person/detail/{client_id:\d+}', new FileLoad($app, '', 'Client_PersonGetDetail'))->setName('ClientPersonGetDetail');

/* ------------------------------------------------------------
 * Meta
 ------------------------------------------------------------*/
$app->get( '/meta/tablescurl', new FileLoad( $app, "", 'Meta_TablesCurlCreate'))->setName('MetaTablesCurlCreate');
$app->get( '/meta/{table}', new FileLoad( $app, "", 'Meta_ClassnameCreate'))->setName('metaClassnameCreate');
$app->get( '/meta/angular/class/{table}', new FileLoad( $app, "", 'Meta_AngularCreateClass'))->setName('metaAngularCreateClass');
$app->get( '/meta/angular/form/{table}/{classname}', new FileLoad( $app, "", 'Meta_AngularCreateForm'))->setName('metaAngularCreateForm');
$app->get( '/meta/angular/service/{table}/{classname}', new FileLoad( $app, "", 'Meta_AngularServiceCreate'))->setName('metaAngularServiceCreate');

$app->get('/hello/{name}', function ($request, $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->get('/', function ($request, $response) {
    return 'CCAPI - Hello';
    
});
