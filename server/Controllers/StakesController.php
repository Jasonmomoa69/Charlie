<?php

namespace Server\Controllers;

use Server\Models\User;
use Server\Models\Stake;
use Server\Models\Stakepause;
use Server\Controllers\Base\SolidController;
use Server\Others\Validators\StakesValidator;

class StakesController extends SolidController 
{

    public function __construct() 
    {
        $this->model = new Stake;
        $this->validator = new StakesValidator(new Stake);
    }

    protected function filter($body, $keysWhitelist)
    {
        return array_filter($body, function ($item, $key) use ($keysWhitelist) {
            return in_array($key, $keysWhitelist);
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function pause($request, $response)
    {
        // create pause using Stake id
        $body = $request->getParsedBody();

        $errors = [];

        if (!isset($body['user_id'])) {
            $errors[] = 'user id is required';
        }

        if (!isset($body['stake_id'])) {
            $errors[] = 'Stake id is required';
        }

        if (!isset($body['profit_per_day'])) {
            $errors[] = 'profit per day id is required';
        }

        if ($errors) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user_id = $body['user_id'] ?? '';
        $Stake_id = $body['stake_id'] ?? '';
        $profit_per_day = $body['profit_per_day'] ?? '';

        $body['start_timestamp'] = time();

        $body = $this->filter($body, ['user_id', 'stake_id', 'start_timestamp', 'profit_per_day']);

        Stakepause::create($body);

        $row = Stake::where('id', $Stake_id)->first();

        if (!$row) {
            $this->data['errors'] = ['not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $row->update(['status' => 3]);

        $user = User::where('id', $user_id)->first();
        $user = User::relationships($user);

        $this->data['data'] = $user;

        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function play($request, $response)
    {
        // check if Stake is paused using pause id
        $body = $request->getParsedBody();

        $errors = [];

        if (!isset($body['user_id'])) {
            $errors[] = 'user id is required';
        }

        if (!isset($body['stake_id'])) {
            $errors[] = 'Stake id is required';
        }

        if ($errors) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user_id = $body['user_id'] ?? '';
        $Stake_id = $body['stake_id'] ?? '';

        $row = Stake::where('id', $Stake_id)->first();

        if (!$row) {
            $this->data['errors'] = ['not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $row->update(['status' => 1]);

        $row = Stakepause::where('stake_id', $Stake_id)->where('end_timestamp', null)->first();

        if (!$row) {
            $this->data['errors'] = ['not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $timestamp = time();
        $row->update(['end_timestamp' => $timestamp]);

        $user = User::where('id', $user_id)->first();
        $user = User::relationships($user);

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    } 

    public function speed($request, $response) {

        $body = $request->getParsedBody(); 

        $errors = [];

        if (!isset($body['id'])) {
            $errors[] = 'id is required';
        }

        if (!isset($body['profit_per_day'])) {
            $errors[] = 'profit per day id is required';
        }

        if ($errors) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $this->model->where('id', $body['id'])->delete();

        $user = $this->model->apiCreate($body);

        $this->data['data'] = $user;
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }


}