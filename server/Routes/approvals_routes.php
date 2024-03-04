<?php

use Server\Controllers\Stock\ApprovalsController; 

$app->post('/api/approvals', ApprovalsController::class . ':create')->add($user_logged_in);