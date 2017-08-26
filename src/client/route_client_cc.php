
$app->get('/cc', new FileLoad($app, '', 'Client_CcGetAll'))->setName('ClientCcGetAll');
$app->get('/cc/{clicc_id}', new FileLoad($app, '', 'Client_CcGet'))->setName('ClientCcGet');
$app->post('/cc', new FileLoad($app, '', 'Client_CcPost'))->setName('ClientCcPost');
$app->delete('/cc', new FileLoad($app, '', 'Client_CcDelete'))->setName('ClientCcDelete');
