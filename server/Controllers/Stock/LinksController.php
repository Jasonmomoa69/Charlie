<?php

namespace Server\Controllers\Stock;

use Server\Models\Simple\Link;
use Server\Others\Validators\ApiValidator;
use Server\Controllers\Base\SolidController;

class LinksController extends SolidController
{
    public function __construct()
    {
        $this->model = new Link;
        $this->validator = new ApiValidator(new Link);
    }
    
}