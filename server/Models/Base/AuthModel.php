<?php

namespace Server\Models\Base;

use Server\Models\User;
use Server\Models\Admin;
use Server\Models\Adminlog;

class AuthModel extends ApiModel {




    // all logged

    public function allow_all_logged_users() {

        if (!isset($_SESSION[$this->authKey]['user_id'])) {
            return false;
        }

        $user = $this->where('id', $_SESSION[$this->authKey]['user_id'])->first(); 

        if (!$user) {
            return false;
        }

        if ($this->authKey === "user") {
            $user = User::relationships($user);
        }

        return $user;

        return [
            'session' => $_SESSION[$this->authKey],
            'data' => $user,
        ];
    }










    // last logged

    public function allow_last_logged_user() {

        if (!isset($_SESSION[$this->authKey]['user_id'])) {
            return false;
        }

        $user = $this->where('id', $_SESSION[$this->authKey]['user_id'])->first(); 

        if (!$user) {
            return false;
        }



        $last_session_id = "";

        if ($this->authKey === "user") {
            $last_session_id = $user->session_id;
        }

        if ($this->authKey === "admin") {
            $logs = Adminlog::latest()->first();

            if ($logs) {
               $last_session_id = $logs->session_id;
            }

        }







        if ($last_session_id != $_SESSION[$this->authKey]['session_id']) {
            return false;
        }

        // $user = $this::relationships($user);

        return $user;

        return [
            'session' => $_SESSION[$this->authKey],
            'data' => $user,
        ];
    }

}