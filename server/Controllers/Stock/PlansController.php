<?php

namespace Server\Controllers\Stock;

use Server\Models\Simple\Plan;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\SolidController;

class PlansController extends SolidController
{

    public function __construct()
    {
        $this->model = new Plan;
        $this->validator = new ApiValidator(new Plan);
    }

}