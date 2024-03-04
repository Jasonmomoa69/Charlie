<?php

use Server\Controllers\Stock\LinksController;

$app->get('/api/links', LinksController::class.':list');

$app->post('/api/links', LinksController::class.':create')->add($admin_logged_in);

$app->delete('/api/links', LinksController::class.':delete')->add($admin_logged_in);

$app->patch('/api/links', LinksController::class.':update')->add($admin_logged_in);
