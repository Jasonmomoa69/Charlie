<?php

use Server\Controllers\Stock\TradersController;

// Traders API

$app->get('/api/traders', TradersController::class . ':list');

$app->post('/api/traders/search', TradersController::class . ':search');

$app->post('/api/traders', TradersController::class . ':create')->add($admin_logged_in);

$app->patch('/api/traders', TradersController::class . ':update')->add($admin_logged_in);

$app->delete('/api/traders', TradersController::class . ':delete')->add($admin_logged_in);




$app->post('/api/copytrading/update', TradersController::class . ':updateCopier')->add($admin_logged_in);

$app->post('/api/copytrading/detach', TradersController::class . ':detachCopier')->add($admin_logged_in);


$app->get('/api/traders/{attr}', TradersController::class . ':read');

$app->post('/api/traders/{attr}', TradersController::class . ':update')->add($admin_logged_in);
