<?php

namespace Server\Routes\Middlewares;

use Server\Models\User;
use Server\Models\Admin;
use GuzzleHttp\Psr7\Response;

class UserOrAdminLoggedIn
{

    public function __invoke($request, $handler)
    {

        $user = (new User)->getAuthState();
        $admin = (new Admin)->getAuthState();

        if ($user === false && $admin === false) {
            $response = new Response;

            $response->getBody()->write(json_encode([
                'status' => "401",
                'errors' => ['Unathorized, Please reload your page then try again']
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        if ($user) {
            $request = $request->withAttribute('user', $user);
        }

        if ($admin) {
            $request = $request->withAttribute('admin', $admin);
        }

        $response = $handler->handle($request);

        return $response;
    }
}