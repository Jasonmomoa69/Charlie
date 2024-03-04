<?php

use Server\Controllers\SettingsController;

$app->get('/api/settings', SettingsController::class . ':list');

$app->patch('/api/settings', SettingsController::class . ':update')->add($admin_logged_in);

$app->patch('/api/settings/currencies', SettingsController::class . ':currencies')->add($admin_logged_in);

$app->patch('/api/settings/withdrawal-methods', SettingsController::class . ':withdrawalMethods')->add($admin_logged_in);
