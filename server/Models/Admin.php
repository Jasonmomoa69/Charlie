<?php

namespace Server\Models;

use Server\Models\Base\AuthModel;

class Admin extends AuthModel
{

    public $authKey = "admin";

    protected $connection = 'cold_database';

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'id',
        'password',
        'created_at',
        'updated_at',
    ];

    public function getAuthState() {
        $user = $this->allow_last_logged_user();
        return $user;
    }

    public function apiCreate($body)
    {
        $body['password'] = sha1($body['password']);
        return $this->create($body);
    }

    public static function relationships($admin)
    {
        return $admin;
    }
}