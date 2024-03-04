<?php

use Server\Controllers\Stock\ReviewsController;

$app->get('/api/reviews', ReviewsController::class . ':list');

$app->post('/api/reviews', ReviewsController::class . ':create')->add($admin_logged_in);

$app->patch('/api/reviews', ReviewsController::class . ':update')->add($admin_logged_in);

$app->delete('/api/reviews', ReviewsController::class . ':delete')->add($admin_logged_in);