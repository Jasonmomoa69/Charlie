<?php
 
namespace Server\Models;

use Server\Models\Traits\CreatedTrait; 
use Illuminate\Database\Eloquent\Model;

class Stake extends Model
{ 

    use CreatedTrait;

    protected $fillable = [
        'name',
        'plan',
        'symbol',
        'power',
        'status',
        'user_id',
        'hashrate',
        'currency',
        'deposited',
        'expiry_date',
        'end_timestamp',
        'profit_per_day',
        'start_timestamp',
        'expiry_timestamp'
    ];

    public function apiCreate($body)
    {
        $body['start_timestamp'] = time();
        
        $row = $this->create($body);

        $user = User::where("id", $row->user_id)->first();
        $user = User::relationships($user);

        return $user;
    }

    public function apiUpdate($body)
    {

        $row = $this->where("id", $body['id'])->first();
        $row->update($body);

        $user = User::where("id", $row->user_id)->first();
        $user = User::relationships($user);

        return $user;
    }

    public function apiDelete($body)
    {
        $row = $this->where("id", $body["id"])->first();
        $user_id = $row->user_id;
        $row->delete();

        $user = User::where('id', $row->user_id)->first();
        $user = User::relationships($user);

        return $user;
    }

    public function pauses()
    {
        return $this->hasMany(Stakepause::class);
    }

    public function getBalanceAttribute()
    {

        $seconds_loss = $this->profit_per_second * $this->seconds_paused;

        $seconds_profit = $this->profit_per_second * $this->seconds_past;

        return $this->deposited - $this->withdrawn + $seconds_profit - $seconds_loss;
    }

    public function getWithdrawnAttribute() 
    {

        $total_withdrawals = 0;

        $withdrawals = Withdrawal::where('user_id', $this->user_id)->where('currency', $this->symbol)->get()->toArray();

        $withdrawals = array_merge([], $withdrawals);
        for ($i = 0; $i < count($withdrawals); $i++) {
            if ($withdrawals[$i]['status'] != "Failed") {
                $total_withdrawals += $withdrawals[$i]['amount'];
            }
        }

        return $total_withdrawals;        
    }

    public function getStatusAttribute($row)
    {
        if ($row == 1) {
            return 'Staking';
        }

        if ($row == 2) {
            return 'Stopped';
        }

        return 'Stopped';
    }

    public function getSecondsPastAttribute()
    {

        return $this->end_timestamp - $this->start_timestamp;
    }

    public function getSecondsPausedAttribute() 
    {
        $paused = 0; 

        foreach ($this->pauses as $pause) {
            $paused += $pause->seconds_past;
        }

        return $paused;
    }

    public function getProfitPerSecondAttribute()
    {
        return round($this->profit_per_day / 24 / 60 / 60, 8);
    }

    public function getEndTimestampAttribute($row)
    {
        if ($row == NULL) {
            return time();
        }

        return $row;
    }










    public function getCurrentTimestampAttribute() {
        return time();
    }

    public function getMinutesPastAttribute()
    {
        if ($this->seconds_past >= 60) {
            return round($this->seconds_past / 60, 2);
        }
    }

    public function getHoursPastAttribute()
    {
        if ($this->minutes_past >= 60) {
            return $this->minutes_past / 60;
        }
    }

    public function getDaysPastAttribute()
    {
        if ($this->hours_past >= 24) {
            return $this->hours_past / 24;
        }
    }


}