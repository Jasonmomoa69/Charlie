<?php

namespace Server\Models\Simple;

use Server\Models\Base\ApiModel;

class Payout extends ApiModel {
    
    protected $fillable = [
        'name',
        'from',
        'color',
        'amount',
        'currency',
        'date_of_deposit',
        'date_of_withdrawal',
        'deposit_amount',
        'withdrawal_amount',
        'wallet_address',
        'link',
        'action'
    ];

}