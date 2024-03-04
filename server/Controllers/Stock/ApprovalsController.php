<?php

namespace Server\Controllers\Stock;

use Server\Models\Simple\Approval;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\SolidController;

class ApprovalsController extends SolidController
{

    public function __construct()
    {
        $this->model = new Approval;
        $this->validator = new ApiValidator(new Approval);
    }

}