<?php

namespace Server\Controllers\Stock;

use Server\Models\Simple\Payout;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\SolidController;

class PayoutsController extends SolidController
{

    public function __construct()
    {
        $this->model = new Payout;
        $this->validator = new ApiValidator(new Payout);
    }

}