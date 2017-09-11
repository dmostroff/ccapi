
$app->get('/baltransferinfo', new FileLoad($app, '', 'Cc_BaltransferinfoGetAll'))->setName('CcBaltransferinfoGetAll');
$app->get('/baltransferinfo/{bal_id}', new FileLoad($app, '', 'Cc_BaltransferinfoGet'))->setName('CcBaltransferinfoGet');
$app->post('/baltransferinfo', new FileLoad($app, '', 'Cc_BaltransferinfoPost'))->setName('CcBaltransferinfoPost');
$app->delete('/baltransferinfo', new FileLoad($app, '', 'Cc_BaltransferinfoDelete'))->setName('CcBaltransferinfoDelete');
