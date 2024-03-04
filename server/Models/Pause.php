<?php

namespace Server\Models;

use Illuminate\Database\Eloquent\Model;

class Pause extends Model
{

    protected $fillable = [
        'contract_id',
        'speed_per_day',
        'end_timestamp',
        'start_timestamp',
    ];

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

    public function getMissedAttribute()
    {
        return $this->speed_per_day * $this->days_past;
    }
}
