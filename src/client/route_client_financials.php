
$app->get('/financials', new FileLoad($app, '', 'Client_FinancialsGetAll'))->setName('ClientFinancialsGetAll');
$app->get('/financials/{financial_id}', new FileLoad($app, '', 'Client_FinancialsGet'))->setName('ClientFinancialsGet');
$app->post('/financials', new FileLoad($app, '', 'Client_FinancialsPost'))->setName('ClientFinancialsPost');
$app->delete('/financials', new FileLoad($app, '', 'Client_FinancialsDelete'))->setName('ClientFinancialsDelete');
