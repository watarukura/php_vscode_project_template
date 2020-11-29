<?php

use App\Action\HomeAction;
use App\Action\UserCreateAction;
use App\Action\UserReadAction;
use Slim\App;

return function (App $app) {
    $app->get('/', HomeAction::class)->setName('home');
    $app->get('/users/{id}', UserReadAction::class)->setName('users-get');
    $app->post('/users', UserCreateAction::class)->setName('users-post');
};
