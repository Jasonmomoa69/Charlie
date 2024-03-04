<?php

use Server\Controllers\Stock\PlansController;

$app->get('/api/plans', PlansController::class . ':list');

$app->post('/api/plans', PlansController::class . ':create')->add($admin_logged_in);

$app->patch('/api/plans', PlansController::class . ':update')->add($admin_logged_in);

$app->delete('/api/plans', PlansController::class . ':delete')->add($admin_logged_in);

$app->get('/api/plans/{attr}', PlansController::class . ':read')->add($admin_logged_in);
