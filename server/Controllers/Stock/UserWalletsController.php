<?php

namespace Server\Controllers\Stock;

use Server\Models\Simple\Userwallet;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\SolidController;

class UserWalletsController extends SolidController
{

    public function __construct()
    {
        $this->model = new Userwallet;
        $this->validator = new ApiValidator(new Userwallet);
    }

}