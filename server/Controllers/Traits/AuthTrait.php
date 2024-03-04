<?php

namespace Server\Controllers\Traits;

trait AuthTrait
{
    protected $authKey;

    public function signOut($request, $response)
    {
        unset($_SESSION[$this->authKey]);

        // update csrf token in database

        $this->data['data'] = "Success";
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function encryptPassword($password)
    {
        return $password;
    }
            
    public function userUpdatePassword($request, $response)
    {
        $body = $request->getParsedBody();
        $user = $request->getAttribute("user") ?? $request->getAttribute("admin");

        $errors = [];
            
        if (!isset($body['password'])) {
            $errors[] = 'password is required';
        }

        if (!isset($body['new_password'])) {
            $errors[] = 'new password is required';
        }

        if (!isset($body['confirm_new_password'])) {
            $errors[] = 'password confirmation is required';
        }

        if ($body['new_password'] != $body['confirm_new_password']) {
            $errors[] = 'passwords do not match';
        }

        if (count($errors)) {
            $this->data['errors'] = $errors;
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $password = $this->encryptPassword($body['password']);
        $new_password = $this->encryptPassword($body['new_password']);

        $user = $this->model->where("password", $password)->where("id", $user->id)->first();
        if (!$user) {
            $this->data['errors'] = ['Old Password is incorrect'];
            $response->getBody()->write(json_encode($this->data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $user->update(['password' => $new_password]);

        $this->data['message'] = "Password Updated";
        $response->getBody()->write(json_encode($this->data));
        return $response->withHeader('Content-Type', 'application/json');
    }

}