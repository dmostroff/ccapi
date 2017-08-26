
$app->get('/transaction', new FileLoad($app, '', 'Cc_TransactionGetAll'))->setName('CcTransactionGetAll');
$app->get('/transaction/{cctrans_id}', new FileLoad($app, '', 'Cc_TransactionGet'))->setName('CcTransactionGet');
$app->post('/transaction', new FileLoad($app, '', 'Cc_TransactionPost'))->setName('CcTransactionPost');
$app->delete('/transaction', new FileLoad($app, '', 'Cc_TransactionDelete'))->setName('CcTransactionDelete');
