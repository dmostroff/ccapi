
$app->get('/cchistory', new FileLoad($app, '', 'Client_CchistoryGetAll'))->setName('ClientCchistoryGetAll');
$app->get('/cchistory/{cchist_id}', new FileLoad($app, '', 'Client_CchistoryGet'))->setName('ClientCchistoryGet');
$app->post('/cchistory', new FileLoad($app, '', 'Client_CchistoryPost'))->setName('ClientCchistoryPost');
$app->delete('/cchistory', new FileLoad($app, '', 'Client_CchistoryDelete'))->setName('ClientCchistoryDelete');
