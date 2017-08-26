
$app->get('/action', new FileLoad($app, '', 'Cc_ActionGetAll'))->setName('CcActionGetAll');
$app->get('/action/{ccaction_id}', new FileLoad($app, '', 'Cc_ActionGet'))->setName('CcActionGet');
$app->post('/action', new FileLoad($app, '', 'Cc_ActionPost'))->setName('CcActionPost');
$app->delete('/action', new FileLoad($app, '', 'Cc_ActionDelete'))->setName('CcActionDelete');
