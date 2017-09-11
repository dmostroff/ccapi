
$app->get('/business', new FileLoad($app, '', 'Client_BusinessGetAll'))->setName('ClientBusinessGetAll');
$app->get('/business/{pbiz_id}', new FileLoad($app, '', 'Client_BusinessGet'))->setName('ClientBusinessGet');
$app->post('/business', new FileLoad($app, '', 'Client_BusinessPost'))->setName('ClientBusinessPost');
$app->delete('/business', new FileLoad($app, '', 'Client_BusinessDelete'))->setName('ClientBusinessDelete');
