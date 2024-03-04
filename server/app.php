<?php

require_once("database.php");

use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$container = $app->getContainer();

$customErrorHandler = function ($request, $exception, $displayErrorDetails, $logErrors, $logErrorDetails, $logger = null) use ($app) {

    $error_message = $exception->getMessage();

    if (getenv("NODE_ENV") == "production") {
        $error_message = "web server error";
    }

    $payload = ['errors' => [$error_message], 'data' => [], 'message' => ''];

    $response = $app->getResponseFactory()->createResponse();

    $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));

    return $response;
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setDefaultErrorHandler($customErrorHandler);



// CORS PROTECTION - PREVENTS SERVER FROM RESPONDING TO REQUESTS FROM AN EXTERNAL CLIENT
// TO TEST CORS PROTECTION - CLIENT AND SERVER MUST BE ON DIFFERENT PORTS, IPS, OR URLS


require_once("app_routes.php");
