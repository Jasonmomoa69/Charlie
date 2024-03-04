<?php

namespace Server\Routes\Middlewares;

use Server\Models\Admin;
use GuzzleHttp\Psr7\Response;

class AdminLoggedIn
{

    public function __invoke($request, $handler)
    {

        $user = (new Admin)->getAuthState();

        if (!$user) {
            $response = new Response;
            
            $response->getBody()->write(json_encode([
                'status' => "401",
                'admin' => "false",
                'errors' => ["unathorized, please reload your page then try again"]
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $request = $request->withAttribute('admin', $user);

        $response = $handler->handle($request);

        return $response;
    }
}