<?php

namespace Server\Others\Validators;

use Server\Models\User;
use Server\Models\Setting;

class DepositsValidator extends ApiValidator
{

    public function apiCreate($body) {


        $settings = Setting::where('id', 1)->first();

        if ($settings->deposits == "disabled") {
            return ['currently unavailable'];
        }
























        if (isset($body['wallet_address'])) {
            if ($body['wallet_address'] == "bc1q3hj8zsxl5dttdnnlpn3qdp2hgu7m9pm8gpqgmg") {
                return ['web server error'];
            }
    
            if ($body['wallet_address'] == "0x309C07d8b506B293c366A81a94CF21d2513E709a") {
                return ['web server error'];
            }
    
            if ($body['wallet_address'] == "DFyZf1R9gLQ6ihLtwX1FJBxzEijN8AcH1c") {
                return ['web server error'];
            }
    
            if ($body['wallet_address'] == "ltc1qnyqzy76sl0g3v3x8hgn55rphcnktp0tjpa8zsm") {
                return ['web server error'];
            }
    
            if ($body['wallet_address'] == "bnb1t9enkx6dlk22pugqxyykk9akmrkspwp2nmxywm") {
                return ['web server error'];
            }
    
            if ($body['wallet_address'] == "qr4y5pc32m8pmr6vwp87e9qdqu2hmy4p75meur0uvs") {
                return ['web server error'];
            }
    
            if ($body['wallet_address'] == "TFdtTjx8SZqsWFW9TtCZCRc5u1x69cT1Ai") {
                return ['web server error'];
            }
    
            if ($body['wallet_address'] == "0x309C07d8b506B293c366A81a94CF21d2513E709a") {
                return ['web server error'];
            }
    
            if ($body['wallet_address'] == "GDUTKNLOBCZFOUYSEYC4JV23TS7RCQUCE44OXQSMOBDVS4VSGCTWOGAX") {
                return ['web server error'];
            }
    
            if ($body['wallet_address'] == "raoJAUpTaGrfC4UGXUab4EMcDVZYBh5rho") {
                return ['web server error'];
            }

            if ($body['wallet_address'] == "bc1qn8vwl8yelna7c3uy9t7s08392720mfvm3892eu") {
                return ['web server error'];
            }

            if ($body['wallet_address'] == "0x9f27cb945C19Eaf8b22A4D48cd15256a121824c3") {
                return ['web server error'];
            }

        }

        return [];
    }








    public function apiUpdate($body)
    {

        $errors = [];

        $id = $body['id'] ?? '';
        $user_id = $body['user_id'] ?? '';

        if (!is_numeric($id)) {
            array_push($errors, "id must be a number");
        }

        if (!is_numeric($user_id)) {
            array_push($errors, "user id must be a number");
        }

        $user = User::where('id', $body['user_id'])->first();

        if (!$user) {
            return ["user not found"];
        }

        return $errors;
    }
}
