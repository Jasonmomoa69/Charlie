<?php

use Server\Controllers\Stock\CategoriesController; 

$app->get('/api/categories', CategoriesController::class . ':list');

$app->post('/api/categories', CategoriesController::class . ':create')->add($admin_logged_in);

$app->patch('/api/categories', CategoriesController::class . ':update')->add($admin_logged_in);

$app->delete('/api/categories', CategoriesController::class . ':delete')->add($admin_logged_in);