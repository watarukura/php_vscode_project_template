<?php

use Slim\App;

return function (App $app) {
    $app->get('/', \App\Action\HomeAction::class)->setName('home');
};

return function (App $app) {
    $app->post('/users', \App\Action\UserCreateAction::class);
};