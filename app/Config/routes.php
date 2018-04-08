<?php
use \EChat\Router\Route;

$approuter = \EChat\Registry::get('approuter');

$approuter->addRoute(
    new Route(
        [
            'match' => ['index', ''],
            'action' => 'index'
        ]
    )
);

$approuter->addRoute(
    new Route(
        [
            'match' => ['login'],
            'action' =>  'login'
        ]
    )
);

$approuter->addRoute(
  new Route(
      [
          'match' => ['message'],
          'action' => 'message'
      ]
  )
);

$approuter->addRoute(
    new Route(
        [
            'match' => ['user'],
            'action' => 'user'
        ]
    )
);