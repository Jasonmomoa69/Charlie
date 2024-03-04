<?php

use Server\Controllers\Stock\TradesController;

$app->get('/api/trades', TradesController::class . ':list')->add($admin_logged_in);

$app->post('/api/trades', TradesController::class . ':create')->add($user_or_admin_logged_in);

$app->patch('/api/trades', TradesController::class . ':update')->add($user_or_admin_logged_in); 

$app->post('/api/trades/summary', TradesController::class . ':summary')->add($admin_logged_in);