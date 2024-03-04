<?php

use Server\Controllers\DepositsController;

$app->get('/api/deposits', DepositsController::class . ':list');

$app->post('/api/deposits', DepositsController::class . ':create')->add($user_logged_in);

$app->patch('/api/deposits', DepositsController::class . ':update')->add($admin_logged_in);
 
$app->delete('/api/deposits', DepositsController::class . ':delete')->add($admin_logged_in);

$app->post('/api/deposits/admin', DepositsController::class . ':adminCreate')->add($admin_logged_in);

$app->post('/api/deposits/proof/{id}', DepositsController::class . ':uploadProof')->add($user_logged_in);