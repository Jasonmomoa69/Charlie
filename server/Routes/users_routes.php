<?php

use Server\Controllers\UsersController;


$app->get('/api/users/send/code', UsersController::class . ':sendCode')->add($user_logged_in);

$app->post('/api/users/send/push', UsersController::class . ':sendPush')->add($admin_logged_in);

$app->patch('/api/users/update/user', UsersController::class . ':userUpdate')->add($user_logged_in);

$app->patch('/api/users/update/admin', UsersController::class . ':adminUpdate')->add($admin_logged_in);

$app->post('/api/users/upload/utility-bill', UsersController::class . ':uploadUtilityBill')->add($user_logged_in);

$app->post('/api/users/upload/identity-card', UsersController::class . ':uploadIdentityCard')->add($user_logged_in);



// Users API

$app->post('/api/users', UsersController::class . ':create');

$app->get('/api/users', UsersController::class . ':list')->add($admin_logged_in);

$app->delete('/api/users', UsersController::class . ':delete')->add($admin_logged_in);

$app->post('/api/users/search', UsersController::class . ':search')->add($admin_logged_in);

$app->get('/api/users/{attr}', UsersController::class . ':read')->add($admin_logged_in);
