
$app->get('/address', new FileLoad($app, '', 'Client_AddressGetAll'))->setName('ClientAddressGetAll');
$app->get('/address/{address_id}', new FileLoad($app, '', 'Client_AddressGet'))->setName('ClientAddressGet');
$app->post('/address', new FileLoad($app, '', 'Client_AddressPost'))->setName('ClientAddressPost');
$app->delete('/address', new FileLoad($app, '', 'Client_AddressDelete'))->setName('ClientAddressDelete');
