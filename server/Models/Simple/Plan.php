<?php

namespace Server\Models\Simple;

use Server\Models\Base\ApiModel;

class Plan extends ApiModel
{

    public $apiOrder = "asc";

    protected $fillable = [
        'type',
        'title',
        'theme',
        'daily',
        'bonus',
        'alerts',
        'comment',
        'referral',
        'price_min',
        'price_max',
        'withdrawal',
        'live_trading'
    ];
}
