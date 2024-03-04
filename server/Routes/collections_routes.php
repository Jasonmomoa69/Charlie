<?php

use Server\Controllers\CollectionsController;

$app->get('/api/collections', CollectionsController::class . ':list');

$app->post('/api/collections', CollectionsController::class . ':create')->add($admin_logged_in);

$app->post('/api/collections/create', CollectionsController::class . ':createEmpty')->add($user_logged_in);

$app->post('/api/collections/publish', CollectionsController::class . ':publish')->add($user_logged_in);

$app->patch('/api/collections', CollectionsController::class . ':update')->add($admin_logged_in);

$app->delete('/api/collections', CollectionsController::class . ':delete')->add($admin_logged_in);

$app->delete('/api/collections/{attr}', CollectionsController::class . ':deleteNft')->add($admin_logged_in);

$app->post('/api/collections/search', CollectionsController::class . ':search');
 
$app->get('/api/collections/{attr}', CollectionsController::class . ':read');