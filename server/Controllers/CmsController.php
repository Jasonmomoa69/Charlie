<?php

namespace Server\Controllers;

use Server\Models\User;
use Server\Models\Admin;
use Server\Models\Wallet;
use Server\Models\Review;
use Server\Others\Sender;
use Server\Models\Setting;
use Server\Others\Renderer;
use Server\Models\Direction;
use Server\Models\Categorie;
use Server\Models\Collection;
use Server\Models\Simple\Link;
use Server\Models\Simple\Page;
use Server\Models\Simple\Plan;
use Server\Models\Simple\Payout;
use Server\Models\Simple\RefdUser;
use Illuminate\Pagination\Paginator;

class CmsController
{

    public $sender;

    public $renderer;

    public $data = [
        'status' => "200",
        'message' => '',
        'errors' => [],
        'data' => [],
    ];

    public function __construct()
    {
        $this->sender = new Sender;

        $this->renderer = new Renderer;
    }

    public function graph($request, $response)
    {
        $data = [];


        $data['admin'] = (new Admin)->getAuthState();





        Paginator::currentPathResolver(function () {
            return "/api/users/auth/status";
        });

        $data['user'] = (new User)->getAuthState();






        Paginator::currentPathResolver(function () {
            return "/api/settings";
        });

        $data['settings'] = (new Setting)->apiList();






        Paginator::currentPathResolver(function () {
            return "/api/wallets";
        });

        $data['wallets'] = (new Wallet)->apiList();







        Paginator::currentPathResolver(function () {
            return "/api/links";
        });

        $data['links'] = (new Link)->apiList();










        Paginator::currentPathResolver(function () {
            return "/api/plans";
        });

        $data['plans'] = (new Plan)->apiList();








        Paginator::currentPathResolver(function () {
            return "/api/payouts";
        });

        $data['payouts'] = (new Payout)->apiList();






        Paginator::currentPathResolver(function () {
            return "/api/reviews";
        });

        $data['reviews'] = (new Review)->apiList()['data'];



        Paginator::currentPathResolver(function () {
            return "/api/directions";
        });

        $data['directions'] = (new Direction)->apiList()['data'];










        $has_nft = getenv("NODE_NFT");
        if ($has_nft === "yes") {

            Paginator::currentPathResolver(function () {
                return "/api/categories";
            });

            $data['categories'] = (new Categorie)->apiList()['data'];




            // Paginator::currentPathResolver(function () {
            //     return "/api/collections";
            // });

            // $data['collections'] = (new Collection)->apiList();
        }









        Paginator::currentPathResolver(function () {
            return "/api/pages";
        });

        $data['pages'] = (new Page)->apiList();






        $response->getBody()->write(json_encode($data));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function sendEmail($request, $response)
    {
        $body = $request->getParsedBody();

        $errors = [];

        if (!isset($body['to'])) {
            $errors[] = 'to is required';
        }

        if (!isset($body['body'])) {
            $errors[] = 'body is required';
        }

        if (!isset($body['subject'])) {
            $errors[] = 'subject is required';
        }


        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $from = $body['from'] ?? NULL;

        $password = $body['password'] ?? NULL;

        $sent = $this->sender->sendEmail([$body['to']], $body['body'], $body['subject'], $from, $password);

        if (!$sent) {
            $this->data['errors'] = ['failed to send'];

            $response->getBody()->write(json_encode($this->data));

            return $response->withHeader('Content-Type', 'application/json');
        }

        $this->data['message'] = 'Sent';

        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    }












    public function renderApp($request, $response)
    {

        $settings = Setting::where('id', 1)->first();

        $data = $this->renderer->render('index.twig', [
            'CHAT_CODE' => $settings->chat_code,
            'META_DESC' => $settings->meta_description 
        ]);

        $response->getBody()->write($data);

        return $response;
    }








    public function renderTemplate($request, $response)
    {

        $row = RefdUser::where('id', 1)->first()->toArray();

        $data = $this->renderer->render('/assets/email/table.twig', ['row' => $row]);

        $response->getBody()->write($data);

        return $response;
    }
}
