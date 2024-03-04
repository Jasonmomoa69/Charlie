<?php

use Server\Models\User;
use Server\Models\Trade;
use Server\Models\Admin;
use Server\Models\Wallet;
use Server\Models\Deposit;
use Server\Models\Direction;
use Server\Others\Connection;
use Server\Models\Withdrawal;
use Server\Models\Simple\Link;
use Server\Others\Observers\ColdObserver;
use Server\Others\Observers\UserObserver;
use Server\Others\Observers\TradeObserver;
use Server\Others\Observers\DepositObserver;
use Server\Others\Observers\WithdrawalObserver;

new Connection;

User::observe(new UserObserver);
Link::observe(new ColdObserver);
Admin::observe(new ColdObserver);
Wallet::observe(new ColdObserver);
Direction::observe(new ColdObserver);


Trade::observe(new TradeObserver);
Deposit::observe(new DepositObserver);
Withdrawal::observe(new WithdrawalObserver);
