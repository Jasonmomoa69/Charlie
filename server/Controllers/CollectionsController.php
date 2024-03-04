<?php

namespace Server\Controllers;

use Server\Models\User;
use Server\Models\Collection;
use Server\Others\Services\Uploader;
use Server\Controllers\Base\SolidController;
use Server\Others\Validators\CollectionsValidator;

class CollectionsController extends SolidController
{

    public function __construct()
    {
        $this->model = new Collection;
        $this->validator = new CollectionsValidator(new Collection);
    }



    public function publish($request, $response) {
        $body = $request->getParsedBody();

        if (!isset($body["token_address"])) {
            $this->data['errors'] = ['token address is required'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $collection = Collection::where('token_address', $body['token_address'])->first();
        if (!$collection) {
            $this->data['errors'] = ['collection not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        
        $user = User::where('id', $body['user_id'])->first();
        if (!$user) {
            $this->data['errors'] = ['user not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        if ($user->nft_balance < $collection->publish_price) {
            $this->data['errors'] = ['you dont have enough funds to publish this collection'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $collection->update(['status' => 'Published']);

        $user->update(['nft_balance' => $user->nft_balance - $collection->publish_price]);



        $user = User::where('id', $body['user_id'])->first();
        $user = User::relationships($user);

        $this->data['data'] = $user;
        $this->data['message'] = 'Collection Created';
        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    }















    public function createEmpty($request, $response)
    {
        $body = $request->getParsedBody();
        
        $user = User::where('id', $body['user_id'])->first();
        if (!$user) {
            $this->data['errors'] = ['user not found'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $uploaderResponse = Uploader::upload('image');

        if (count($uploaderResponse['errors'])) {
            $this->data['errors'] = $uploaderResponse['errors'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $body['status'] = 'Draft';
        $body['token_address'] = time();
        $body['image'] = $uploaderResponse['fullname'];
        $data = $this->model->create($body);

        $user = User::where('id', $user->id)->first();
        $user = User::relationships($user);
        $this->data['data'] = $user;
        $this->data['message'] = 'Collection Created';
        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    } 
























    public function create($request, $response)
    {
        $body = $request->getParsedBody();
        $errors = $this->validator->apiCreate($body);

        if (!isset($body["token_address"])) {
            $this->data['errors'] = ['token address is required'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $data = $this->model->apiCreate($body);

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    } 


    public function deleteNft($request, $response)
    {
        $body = $request->getParsedBody();

        $errors = [];

        if (!isset($body["id"])) {
            $errors[] = ['id is required'];
        }

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $data = $this->model->apiDeleteNft($body);

        $this->data['data'] = $data;
        
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    
 
}