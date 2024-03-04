<?php

namespace Server\Others\Validators;

class TradesValidator extends ApiValidator
{

    public function apiCreate($body) {


        if (isset($body['time'])) {
            if (!is_numeric($body['time'])) {
                return ['time must be a number'];
            }

            if ($body['time'] <= 0) {
                return ['time must be a positive'];
            }
        }

        if (!isset($body['chart'])) {
            return ['chart is required'];
        }

        if (!isset($body['opening_price'])) {
            return ['opening price is required'];
        }

        if ($body['amount'] < 0) {
            return ['amount must be greater than zero'];
        }

        if (!is_numeric($body['amount'])) {
            return ['amount must be a number'];
        }

        if ($body['amount'] <= 0) {
            return ['amount must be a positive'];
        }


        return [];
    }















    public function apiUpdate($body)
    {
        $errors = [];

        if (!isset($body['id'])) {
            return ['id is required'];
        }

        return $errors;
    }
}