<?php

namespace Server\Models;

use Server\Models\Traits\CreatedTrait;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{

    use CreatedTrait;

    protected $fillable = [
        'plan',
        'added',
        'power',
        'status',
        'user_id',
        'hashrate',
        'currency',
        'expiry_date',
        'end_timestamp',
        'speed_per_day',
        'start_timestamp',
        'expiry_timestamp'
    ];

    public function getMinedAttribute()
    {

        $paused = 0; 

        foreach ($this->pauses as $pause) {
            $paused += $pause->missed;
        }

        return $this->speed_per_day * $this->days_past + $this->added - $paused;
    }

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

    public function getEndTimestampAttribute($row)
    {
        if ($row == NULL) {
            return time();
        }

        return $row;
    }

    public function getSecondsPastAttribute()
    {
        return $this->end_timestamp - $this->start_timestamp;
    }

    public function getMinutesPastAttribute()
    {
        return $this->seconds_past / 60;
    }

    public function getHoursPastAttribute()
    {
        return $this->minutes_past / 60;
    }

    public function getDaysPastAttribute()
    {
        return $this->hours_past / 24;
    }



    public function getSpeedPerSecondAttribute()
    {

        if ($this->status == "Stopped") {
            return 0;
        }

        return $this->speed_per_day / 24 / 60 / 120;
    }

    public function getStatusAttribute($row)
    {
        if ($row == 1) {
            return 'Mining';
        }

        if ($row == 2) {
            return 'Stopped';
        }

        return 'Stopped';
    }

    public function pauses()
    {
        return $this->hasMany(Pause::class);
    }
}