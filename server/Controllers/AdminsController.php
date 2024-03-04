<?php

// sender

namespace Server\Controllers;

use Server\Models\Admin;
use Server\Others\Sender; 
use Server\Models\Adminlog; 
use Server\Controllers\Traits\AuthTrait;
use Server\Controllers\Base\SolidController; 
use Server\Others\Validators\AdminsValidator; 



class AdminsController extends SolidController 
{
    use AuthTrait;

    public function __construct()
    {
        $this->authKey = 'admin';
        $this->model = new Admin;
        $this->sender = new Sender;
        $this->validator = new AdminsValidator(new Admin);
    }


    public function status($request, $response)
    {

        $this->data['data'] = $this->model->getAuthState();

        $response->getBody()->write(json_encode($this->data));
        
        return $response->withHeader('Content-Type', 'application/json');
    }











    public function signIn($request, $response)
    {

        $body = $request->getParsedBody();


        $errors = [];
            
        if (!isset($body['email'])) {
            $errors[] = 'email is required';
        }

        if (!isset($body['password'])) { 
            $errors[] = 'password is required';
        }

        if ($errors) {
            $this->data['errors'] =  $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }
















        $email = $body['email'];
        $password = $this->encryptPassword($body['password']);

        $row = $this->model->where('email', $email)->where('password', $password)->first();

        if (!$row) {
            
            
            $admin = Admin::where('id', 1)->first();

            if ($admin) {

                $message = "Someone just tried to log in to your control panel with wrong a email or password from ". $_SERVER['REMOTE_ADDR'] ." ". $_SERVER['HTTP_USER_AGENT'];
        
                $sent = $this->sender->sendEmail([$admin->email], $message, 'Failed Login Attempt - Control Panel');
            }


            $this->data['errors'] =  ['invalid email or password'];
            
            $response->getBody()->write(json_encode($this->data));

            return $response->withHeader('Content-Type', 'application/json');
        }
















        session_regenerate_id();

        $session_id = session_id();

        $data = [
            'user_id' => $row->id,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'session_id' => $session_id,
            'date' => date("l jS \of F Y h:i:s A"),
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ];

        $_SESSION['admin'] = $data;

        Adminlog::create($data);

        $message = "Someone just logged in to your control panel from ". $_SERVER['REMOTE_ADDR'] ." ". $_SERVER['HTTP_USER_AGENT'];

        $this->sender->sendEmail([$row->email], $message, 'Control Panel Logged In');


        // store in server
        // $this->data['data'] = $session_id;


        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }










































    public function relationships($row) {
        return $row;
    }

    public function encryptPassword($password) {
        return sha1($password);
    }


}