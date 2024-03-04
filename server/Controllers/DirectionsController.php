<?php

namespace Server\Controllers;

use Server\Models\Direction;
use Server\Controllers\Base\ApiController;

class DirectionsController extends ApiController
{

    public function __construct()
    {
        $this->model = new Direction;
    }
}
