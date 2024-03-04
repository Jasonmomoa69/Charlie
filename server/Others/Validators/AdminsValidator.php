<?php

namespace Server\Others\Validators;

use Server\Models\Admin;

class AdminsValidator extends ApiValidator
{

    public function apiCreate($body)
    {
        
        $domain = getenv("NODE_DOMAIN");
        $email = str_replace("https://", "info@", $domain);
        $email = str_replace("/", "", $email);

        $admin = Admin::where('email', $email)->first();

        if ($admin) {
            return ["admin already exists"];
        }

        return [];
    }
}