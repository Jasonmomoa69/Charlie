<?php

use Server\Controllers\Stock\WithdrawalsController;

$app->post('/api/withdrawals', WithdrawalsController::class . ':create')->add($user_logged_in);

$app->get('/api/withdrawals', WithdrawalsController::class . ':list')->add($admin_logged_in);

$app->patch('/api/withdrawals', WithdrawalsController::class . ':update')->add($admin_logged_in);
