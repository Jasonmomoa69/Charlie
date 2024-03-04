<?php

namespace Server\Controllers;

use Server\Models\Wallet;
use Server\Controllers\Base\SolidController;
use Server\Others\Validators\WalletsValidator;

class WalletsController extends SolidController
{
    public function __construct()
    {
        $this->model = new Wallet;
        $this->validator = new WalletsValidator(new Wallet);
    }

    public function all($request, $response)
    {
        $data = $this->model->list();
        
        $this->data['data'] = $data;
        
        $response->getBody()->write(json_encode($this->data));

        return $response->withHeader('Content-Type', 'application/json');
    }

    
}