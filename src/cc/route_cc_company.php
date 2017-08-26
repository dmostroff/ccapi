
$app->get('/company', new FileLoad($app, '', 'Cc_CompanyGetAll'))->setName('CcCompanyGetAll');
$app->get('/company/{cc_company_id}', new FileLoad($app, '', 'Cc_CompanyGet'))->setName('CcCompanyGet');
$app->post('/company', new FileLoad($app, '', 'Cc_CompanyPost'))->setName('CcCompanyPost');
$app->delete('/company', new FileLoad($app, '', 'Cc_CompanyDelete'))->setName('CcCompanyDelete');
