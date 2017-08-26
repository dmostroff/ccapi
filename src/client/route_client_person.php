
$app->get('/person', new FileLoad($app, '', 'Client_PersonGetAll'))->setName('ClientPersonGetAll');
$app->get('/person/{client_id}', new FileLoad($app, '', 'Client_PersonGet'))->setName('ClientPersonGet');
$app->post('/person', new FileLoad($app, '', 'Client_PersonPost'))->setName('ClientPersonPost');
$app->delete('/person', new FileLoad($app, '', 'Client_PersonDelete'))->setName('ClientPersonDelete');
