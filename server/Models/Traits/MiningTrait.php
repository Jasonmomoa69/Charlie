<?php

namespace Server\Models\Traits;

use Server\Models\Contract;
use Server\Models\Withdrawal;

trait MiningTrait {

    public function calculateMiningBalance($currency)
    {
        $total_mined = 0;
        $total_withdrawals = 0; 

        $mining_contracts =  Contract::where('user_id', $this->id)->where('currency', $currency)->get()->toArray();
        $mining_contracts = array_merge([], $mining_contracts);
        for ($i = 0; $i < count($mining_contracts); $i++) {
            $total_mined += $mining_contracts[$i]['mined'];
        }

        $mining_withdrawals = Withdrawal::where('user_id', $this->id)->where('currency', $currency)->get()->toArray();
        $mining_withdrawals = array_merge([], $mining_withdrawals);
        for ($i = 0; $i < count($mining_withdrawals); $i++) {
            if ($mining_withdrawals[$i]['status'] != "Failed") {
                $total_withdrawals += $mining_withdrawals[$i]['amount'];
            }
        }

        $total_mined = $total_mined - $total_withdrawals;

        // return $total_mined;
        return round($total_mined, 6);
    }

    public function calculateMiningHashrate($currency)
    {
        $hashrate = 0;

        $mining_contracts =  Contract::where('user_id', $this->id)->where('currency', $currency)->get()->toArray();

        $mining_contracts = array_merge([], $mining_contracts);
        for ($i = 0; $i < count($mining_contracts); $i++) {
            $hashrate += $mining_contracts[$i]['hashrate'];
        }

        return $hashrate;
    }

    public function calculateMiningSpeedPerSecond($currency)
    {
        $speed_per_second = 0;

        $mining_contracts =  Contract::where('user_id', $this->id)->where('currency', $currency)->get()->toArray();

        $mining_contracts = array_merge([], $mining_contracts);
        for ($i = 0; $i < count($mining_contracts); $i++) {
            $speed_per_second += $mining_contracts[$i]['speed_per_second'];
        }

        return $speed_per_second;
    }


    // mining balance

    public function getMiningBalanceBnbAttribute($row)
    {

        if ($row > 0) {
            return $row;
        } 

        return $this->calculateMiningBalance("BNB");
    }
    
    public function getMiningBalanceBtcAttribute($row)
    {

        if ($row > 0) {
            return $row;
        } 

        return $this->calculateMiningBalance("BTC");
    }

    public function getMiningBalanceEthAttribute($row)
    {
        
        if ($row > 0) {
            return $row;
        } 

        return $this->calculateMiningBalance("ETH");
    }

    public function getMiningBalanceDogeAttribute($row)
    {

        if ($row > 0) {
            return $row;
        } 

        return $this->calculateMiningBalance("DOGE");
    }

    public function getMiningBalanceAtomAttribute($row)
    {

        if ($row > 0) {
            return $row;
        } 

        return $this->calculateMiningBalance("ATOM");
    }


    // mining hashrate

    public function getMiningHashrateBnbAttribute()
    {
        return $this->calculateMiningHashrate("BNB");
    }

    public function getMiningHashrateBtcAttribute()
    {
        return $this->calculateMiningHashrate("BTC");
    }

    public function getMiningHashrateEthAttribute()
    {
        return $this->calculateMiningHashrate("ETH");
    }

    public function getMiningHashrateDogeAttribute()
    {
        return $this->calculateMiningHashrate("DOGE");
    }

    public function getMiningHashrateAtomAttribute()
    {
        return $this->calculateMiningHashrate("ATOM");
    }


    // mining speed

    public function getMiningSpeedPsBtcAttribute()
    {
        return $this->calculateMiningSpeedPerSecond("BTC");
    }

    public function getMiningSpeedPsEthAttribute()
    {
        return $this->calculateMiningSpeedPerSecond("ETH");
    }

    public function getMiningSpeedPsDogeAttribute()
    {
        return $this->calculateMiningSpeedPerSecond("DOGE");
    }

    public function getMiningSpeedPsBnbAttribute()
    {
        return $this->calculateMiningSpeedPerSecond("BNB");
    }

    public function getMiningSpeedPsAtomAttribute()
    {
        return $this->calculateMiningSpeedPerSecond("ATOM");
    }



}