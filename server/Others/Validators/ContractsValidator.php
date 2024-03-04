<?php

namespace Server\Others\Validators;

use Server\Models\Contract;

class ContractsValidator extends ApiValidator
{

    public function apiCreate($body)
    {

        if (!isset($body['currency'])) {
            return ['Currency is required'];
        }

        if (!isset($body['user_id'])) {
            return ['User ID is required'];
        }

        $contract = Contract::where('currency', $body['currency'])->where('user_id', $body['user_id'])->first();    
        if ($contract) {
            return ['1 Contract Per Coin Per User'];
        }

        return [];
    }

}