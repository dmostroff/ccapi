
$app->get('/cards', new FileLoad($app, '', 'Cc_CardsGetAll'))->setName('CcCardsGetAll');
$app->get('/cards/{cc_card_id}', new FileLoad($app, '', 'Cc_CardsGet'))->setName('CcCardsGet');
$app->post('/cards', new FileLoad($app, '', 'Cc_CardsPost'))->setName('CcCardsPost');
$app->delete('/cards', new FileLoad($app, '', 'Cc_CardsDelete'))->setName('CcCardsDelete');
