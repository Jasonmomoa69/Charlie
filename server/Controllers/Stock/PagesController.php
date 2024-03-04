<?php

namespace Server\Controllers\Stock;

use Server\Models\Simple\Page;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\SolidController;

class PagesController extends SolidController
{

    public function __construct()
    {
        $this->model = new Page;
        $this->validator = new ApiValidator(new Page);
    }

}