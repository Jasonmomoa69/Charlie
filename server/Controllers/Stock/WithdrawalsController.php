<?php

namespace Server\Controllers\Stock;

use Server\Models\Withdrawal;
use Server\Controllers\Base\SolidController;
use Server\Others\Validators\WithdrawalsValidator;

class WithdrawalsController extends SolidController
{

    public function __construct()
    {
        $this->model = new Withdrawal;
        $this->validator = new WithdrawalsValidator(new Withdrawal);
    }

}