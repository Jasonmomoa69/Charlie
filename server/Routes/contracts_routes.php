<?php

use Server\Controllers\ContractsController;

$app->post('/api/contracts', ContractsController::class . ':create')->add($admin_logged_in);

$app->patch('/api/contracts', ContractsController::class . ':update')->add($admin_logged_in);

$app->delete('/api/contracts', ContractsController::class . ':delete')->add($admin_logged_in);

$app->patch('/api/contracts/play', ContractsController::class . ':play')->add($admin_logged_in);

$app->post('/api/contracts/pause', ContractsController::class . ':pause')->add($admin_logged_in);

$app->patch('/api/contracts/speed', ContractsController::class . ':speed')->add($admin_logged_in);

// $app->delete('/api/contracts/stop', ContractsController::class . ':stop')->add($admin_logged_in);