<?php

namespace Server\Others\Validators;

class WalletsValidator extends ApiValidator
{

    public function apiCreate($body) {

        if (!isset($body['symbol'])) {
            return ['symbol is required'];
        }

        if (!isset($body['fullname'])) {
            return ['full name is required'];
        }

        if (!isset($body['address'])) {
            return ['wallet address is required'];
        }
        
        if (preg_match("/[^a-zA-Z]/", $body['symbol'])) {
            return ['symbol contains invalid characters'];
        }

        return [];
    }



    public function apiUpdate($body) {

        if (!isset($body['symbol'])) {
            return ['symbol is required'];
        }

        if (!isset($body['fullname'])) {
            return ['full name is required'];
        }

        if (!isset($body['address'])) {
            return ['wallet address is required'];
        }

        if (preg_match("/[^a-zA-Z]/", $body['symbol'])) {
            return ['symbol contains invalid characters'];
        }

        return [];
    }

}