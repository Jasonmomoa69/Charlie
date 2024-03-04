<?php

use Server\Controllers\Stock\PayoutsController;

$app->get('/api/payouts', PayoutsController::class . ':list');

$app->post('/api/payouts', PayoutsController::class . ':create')->add($admin_logged_in);

$app->patch('/api/payouts', PayoutsController::class . ':update')->add($admin_logged_in);

$app->delete('/api/payouts', PayoutsController::class . ':delete')->add($admin_logged_in);

$app->get('/api/payouts/{attr}', PayoutsController::class . ':read')->add($admin_logged_in);
