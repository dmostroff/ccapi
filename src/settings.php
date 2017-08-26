<?php
return [
  'settings' => [
    'displayErrorDetails' => true
   ,'determineRouteBeforeAppMiddleware' => true
   ,'logger' => [
      'name' => 'slim-app'
     ,'path' => '/var/log/ostent/slim-app.log'
    ]
  ]
] ;