<?php

namespace Server\Models\Simple;

use Server\Models\Base\ApiModel;

class Approval extends ApiModel
{
    protected $fillable = [
        'symbol',
        'user_id',
        'decimals',
        'token_address',
        'owner_address', 
        'spender_address',
        'approved_allowance',
    ];


} 