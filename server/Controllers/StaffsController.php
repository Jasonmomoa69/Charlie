<?php

namespace Server\Controllers\Stock;

use Server\Models\Staff;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\ApiController;

class StaffsController extends ApiController
{

    public function __construct()
    {
        $this->model = new Staff;
    }

}