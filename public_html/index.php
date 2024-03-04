<?php

session_start();

define("HTML_DIR", __DIR__);

define("EMAIL_DIR", __DIR__ . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "email" . DIRECTORY_SEPARATOR);

define("IMAGE_DIR", __DIR__ . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR);

require_once('../vendor/autoload.php');

require_once('../server/app.php');

$app->run();