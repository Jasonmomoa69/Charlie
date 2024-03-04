<?php

namespace Server\Controllers\Stock;

use Server\Models\Trade;
use Server\Controllers\Base\SolidController;
use Server\Others\Validators\TradesValidator;

class TradesController extends SolidController
{ 

    public function __construct()
    {
        $this->model = new Trade;
        $this->validator = new TradesValidator(new Trade);
    }


}