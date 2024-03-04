<?php

use Server\Controllers\TraderUserController;

$app->post('/api/traderuser/update', TraderUserController::class . ':update')->add($admin_logged_in);

$app->post('/api/traderuser/copy', TraderUserController::class . ':copy')->add($user_or_admin_logged_in);

$app->post('/api/traderuser/uncopy', TraderUserController::class . ':uncopy')->add($user_or_admin_logged_in);