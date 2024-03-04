<?php

use Server\Controllers\Stock\UserWalletsController; 

$app->post('/api/userwallets', UserWalletsController::class . ':create')->add($user_logged_in);