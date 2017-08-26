
$app->get('/users', new FileLoad($app, '', 'Adm_UsersGetAll'))->setName('AdmUsersGetAll');
$app->get('/users/{user_id}', new FileLoad($app, '', 'Adm_UsersGet'))->setName('AdmUsersGet');
$app->post('/users', new FileLoad($app, '', 'Adm_UsersPost'))->setName('AdmUsersPost');
$app->delete('/users', new FileLoad($app, '', 'Adm_UsersDelete'))->setName('AdmUsersDelete');
