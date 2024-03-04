<?php

namespace Server\Controllers\Stock;

use Server\Models\Trader;
use Server\Controllers\Base\ApiController;

class TradersController extends ApiController
{

    public function __construct()
    {
        $this->model = new Trader;
    }
}
