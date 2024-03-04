<?php

use Server\Controllers\UsersController;

// Auth

$app->get('/api/users/auth/status', UsersController::class . ':status');

$app->post('/api/users/auth/signin', UsersController::class . ':signin');

$app->get('/api/users/auth/signout', UsersController::class . ':signout')->add($user_logged_in);



// Updates

$app->post('/api/users/auth/update/photo', UsersController::class . ':updatePhoto')->add($user_logged_in);

$app->patch('/api/users/auth/update/email', UsersController::class . ':updateEmail')->add($user_logged_in);

$app->patch('/api/users/auth/update/password-user', UsersController::class . ':userUpdatePassword')->add($user_logged_in);

$app->patch('/api/users/auth/update/password-guest', UsersController::class . ':guestUpdatePassword');





// Pin Related

$app->post('/api/users/auth/send-pin', UsersController::class . ':sendPin');

$app->post('/api/users/auth/verify-email', UsersController::class . ':verifyEmail')->add($user_logged_in);

$app->patch('/api/users/auth/verify-admin', UsersController::class . ':verifyAdmin')->add($admin_logged_in);
