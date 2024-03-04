<?php

namespace Server\Others\Validators;

use Server\Models\Stake;

class StakesValidator extends ApiValidator
{

    public function apiCreate($body) 
    {

        if (!isset($body['symbol'])) {
            return ['Currency is required'];
        }

        if (!isset($body['user_id'])) {
            return ['User ID is required'];
        }

        $body['symbol'] = \strtolower($body['symbol']);

        $Stake = Stake::where('symbol', $body['symbol'])->where('user_id', $body['user_id'])->first();    
        if ($Stake) {
            return ['1 Stake Per Coin Per User'];
        }

        return [];
    }

}