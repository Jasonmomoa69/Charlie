<?php

namespace Server\Routes\Middlewares;

use Server\Models\User;
use GuzzleHttp\Psr7\Response;

class UserLoggedIn 
{

    public function __invoke($request, $handler)
    {
        $user = (new User)->getAuthState();

        if (!$user) {
            $response = new Response;
            
            $response->getBody()->write(json_encode([
                'status' => "401",
                'user' => "false",
                'errors' => ['unathorized, please reload your page then try again']
            ]));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        //$user = User::relationships($user);

        $request = $request->withAttribute('user', $user);

        $response = $handler->handle($request);

        return $response;
    }
}