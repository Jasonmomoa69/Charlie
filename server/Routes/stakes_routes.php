<?php

use Server\Controllers\StakesController;

$app->post('/api/stakes', StakesController::class . ':create')->add($admin_logged_in);

$app->patch('/api/stakes', StakesController::class . ':update')->add($admin_logged_in);

$app->delete('/api/stakes', StakesController::class . ':delete')->add($admin_logged_in);

$app->patch('/api/stakes/play', StakesController::class . ':play')->add($admin_logged_in);

$app->post('/api/stakes/pause', StakesController::class . ':pause')->add($admin_logged_in);

$app->patch('/api/stakes/speed', StakesController::class . ':speed')->add($admin_logged_in);

// $app->delete('/api/stakes/stop', StakesController::class . ':stop')->add($admin_logged_in);