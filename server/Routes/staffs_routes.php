<?php

use Server\Controllers\Stock\StaffsController;

$app->get('/api/staffs', StaffsController::class . ':list');

$app->post('/api/staffs', StaffsController::class . ':create')->add($admin_logged_in);

$app->patch('/api/staffs', StaffsController::class . ':update')->add($admin_logged_in);

$app->delete('/api/staffs', StaffsController::class . ':delete')->add($admin_logged_in);