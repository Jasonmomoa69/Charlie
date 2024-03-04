<?php

namespace Server\Controllers;

use Server\Models\User;
use Server\Models\Trader;
use Server\Models\Setting;
use Server\Models\Simple\TraderUser;

class TraderUserController
{

    public $data;

    public function __construct()
    {
        $this->data = [
            'errors' => [],
            'message' => '',
            'data' => (object) [],
        ];
    }

    public function copy($request, $response)
    {
        $body = $request->getParsedBody();

        $user_id = $body['user_id'] ?? '';
        $trader_id = $body['trader_id'] ?? '';

        $user = User::where('id', $user_id)->first();

        if (!$user) {
            $this->data['errors'] = ['user not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $copying = TraderUser::where('trader_id', $trader_id)->where('user_id', $user_id)->first();

        $settings = Setting::where('id', 1)->first();

        if (!$copying) {
            TraderUser::create([
                'user_id' => $user_id,
                'trader_id' => $trader_id,
                'status' => $settings->copy_trading
            ]);
        }

        $user = User::where('id', $user_id)->first();
        $user = User::relationships($user);

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }











    public function uncopy($request, $response)
    {
        $body = $request->getParsedBody();
        $user_id = $body['user_id'] ?? '';
        $trader_id = $body['trader_id'] ?? '';

        $copying = TraderUser::where('trader_id', $trader_id)->where('user_id', $user_id)->first();
        if ($copying) {
            $copying->delete();
        }

        $user = User::where('id', $user_id)->first();

        if (!$user) {
            $this->data['errors'] = ['user not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user = User::relationships($user);

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }












    public function update($request, $response)
    {
        $body = $request->getParsedBody();

        $status = $body['status'] ?? '';
        $user_id = $body['user_id'] ?? '';
        $trader_id = $body['trader_id'] ?? '';

        $copying = TraderUser::where('trader_id', $trader_id)->where('user_id', $user_id)->first();
        if ($copying) {
            $copying->update(['status' => $status]);
        }

        $trader = Trader::where('id', $trader_id)->first();

        if (!$trader) {
            $this->data['errors'] = ['trader not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $trader = Trader::relationships($trader);

        $this->data['data'] = $trader;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }



}
