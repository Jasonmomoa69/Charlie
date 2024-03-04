<?php

use Server\Controllers\Stock\NftsController;

$app->get('/api/nfts/{attr}', NftsController::class . ':read');

$app->post('/api/nfts', NftsController::class . ':create')->add($user_logged_in);

$app->patch('/api/nfts', NftsController::class . ':update')->add($admin_logged_in);

