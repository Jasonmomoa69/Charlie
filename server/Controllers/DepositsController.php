<?php

// uploader

namespace Server\Controllers;

use Server\Models\User;
use Server\Models\Deposit;
use Server\Others\Services\Uploader;
use Server\Controllers\Base\SolidController;
use Server\Others\Validators\DepositsValidator;

class DepositsController extends SolidController
{

    public function __construct()
    {
        $this->model = new Deposit;
        $this->validator = new DepositsValidator(new Deposit);
    }

    public function create($request, $response)
    {
        $body = $request->getParsedBody();
        $user = $request->getAttribute('user');

        $errors = $this->validator->apiCreate($body);

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        // filter status out
        if (isset($body['status'])) {
            unset($body['status']);
        }

        $body['user_id'] = $user->id;
        $data = $this->model->apiCreate($body);
        $this->data['data'] = $data;

        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function adminCreate($request, $response)
    {
        $body = $request->getParsedBody();

        $errors = $this->validator->apiCreate($body);

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        // allow status
        $data = $this->model->apiCreate($body);

        $this->data['data'] = $data;

        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }









    public function uploadProof($request, $response)
    {

        $id = $request->getAttribute('id');
        $user = $request->getAttribute('user');

        $user = User::where('id', $user->id)->first();

        $deposit = $this->model->where('id', $id)->first();
        if (!$deposit) {
            $this->data['errors'] = ['deposit Not Found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }








        $uploaderResponse = Uploader::upload('proof');

        if (count($uploaderResponse['errors'])) {
            $this->data['errors'] = $uploaderResponse['errors'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $body['proof'] = $uploaderResponse['fullname'];








        $deposit->update(['proof' => $body['proof']]);

        $user = User::where('id', $user->id)->first();
        $user = User::relationships($user);

        $this->data['data'] = $user;
        $this->data['message'] = "Upload Successful";

        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function list($request, $response)
    {

        $list = $this->model->where('wallet_address', '!=', null)->where('wallet_address', '!=', '')->select("wallet_address")->get()->unique("wallet_address")->values()->map(function ($item, $key) {
            return ['address' => $item['wallet_address']];
        });

        $response->getBody()->write(json_encode($list));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
