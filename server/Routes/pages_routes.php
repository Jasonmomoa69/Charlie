<?php

use Server\Controllers\Stock\PagesController;

$app->get('/api/pages', PagesController::class . ':list');

$app->post('/api/pages', PagesController::class . ':create')->add($admin_logged_in);

$app->patch('/api/pages', PagesController::class . ':update')->add($admin_logged_in);

$app->delete('/api/pages', PagesController::class . ':delete')->add($admin_logged_in);