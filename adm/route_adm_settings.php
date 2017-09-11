
$app->get('/settings', new FileLoad($app, '', 'Adm_SettingsGetAll'))->setName('AdmSettingsGetAll');
$app->get('/settings/{prefix}/{keyname}', new FileLoad($app, '', 'Adm_SettingsGet'))->setName('AdmSettingsGet');
$app->post('/settings', new FileLoad($app, '', 'Adm_SettingsPost'))->setName('AdmSettingsPost');
$app->delete('/settings', new FileLoad($app, '', 'Adm_SettingsDelete'))->setName('AdmSettingsDelete');
