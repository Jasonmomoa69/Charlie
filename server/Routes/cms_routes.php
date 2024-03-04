<?php

use Server\Controllers\CmsController;

$app->get('/api/graph', CmsController::class . ':graph');

$app->post('/api/contact', CmsController::class . ':sendEmail');

$app->get('/render', CmsController::class . ':renderTemplate');

$app->get('/[{path:.*}]', CmsController::class . ':renderApp');
