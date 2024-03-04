<?php

namespace Server\Controllers;

use Server\Models\User;
use Server\Models\Pause;
use Server\Models\Contract;
use Server\Controllers\Base\SolidController;
use Server\Others\Validators\ContractsValidator;

class ContractsController extends SolidController
{

    public function __construct() 
    {
        $this->model = new Contract;
        $this->validator = new ContractsValidator(new Contract);
    }

    protected function filter($body, $keysWhitelist)
    {
        return array_filter($body, function ($item, $key) use ($keysWhitelist) {
            return in_array($key, $keysWhitelist);
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function pause($request, $response)
    {
        // create pause using contract id
        $body = $request->getParsedBody();

        $errors = [];

        if (!isset($body['user_id'])) {
            $errors[] = 'user id is required';
        }

        if (!isset($body['contract_id'])) {
            $errors[] = 'contract id is required';
        }

        if (!isset($body['speed_per_day'])) {
            $errors[] = 'speed per day id is required';
        }

        if ($errors) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user_id = $body['user_id'] ?? '';
        $contract_id = $body['contract_id'] ?? '';
        $speed_per_day = $body['speed_per_day'] ?? '';

        $body['start_timestamp'] = time();

        $body = $this->filter($body, ['user_id', 'contract_id', 'start_timestamp', 'speed_per_day']);

        Pause::create($body);

        $row = Contract::where('id', $contract_id)->first();

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
        // check if contract is paused using pause id
        $body = $request->getParsedBody();

        $errors = [];

        if (!isset($body['user_id'])) {
            $errors[] = 'user id is required';
        }

        if (!isset($body['contract_id'])) {
            $errors[] = 'contract id is required';
        }

        if ($errors) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user_id = $body['user_id'] ?? '';
        $contract_id = $body['contract_id'] ?? '';

        $row = Contract::where('id', $contract_id)->first();

        if (!$row) {
            $this->data['errors'] = ['not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $row->update(['status' => 1]);

        $row = Pause::where('contract_id', $contract_id)->where('end_timestamp', null)->first();

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

        if (!isset($body['speed_per_day'])) {
            $errors[] = 'speed per day id is required';
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