<?php

use Server\Controllers\DirectionsController;

$app->get('/api/directions', DirectionsController::class . ':list');

$app->post('/api/directions', DirectionsController::class . ':create')->add($admin_logged_in);

$app->patch('/api/directions', DirectionsController::class . ':update')->add($admin_logged_in);

$app->delete('/api/directions', DirectionsController::class . ':delete')->add($admin_logged_in);
