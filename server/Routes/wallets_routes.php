<?php

use Server\Controllers\WalletsController;

$app->get('/api/wallets', WalletsController::class.':list');

$app->get('/api/wallets/all', WalletsController::class.':all')->add($admin_logged_in);

$app->post('/api/wallets', WalletsController::class.':create')->add($admin_logged_in);

$app->delete('/api/wallets', WalletsController::class.':delete')->add($admin_logged_in);

$app->patch('/api/wallets', WalletsController::class.':update')->add($admin_logged_in);
