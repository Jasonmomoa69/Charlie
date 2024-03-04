<?php

namespace Server\Models;

use Illuminate\Database\Eloquent\Model;

class Stakepause extends Model
{

    protected $fillable = [
        'stake_id',
        'speed_per_day',
        'end_timestamp',
        'start_timestamp',
    ];

    public function getEndTimestampAttribute($row)
    {
        if ($row == NULL) { return time();}

        return $row;
    }

    public function getSecondsPastAttribute()
    {
        return $this->end_timestamp - $this->start_timestamp;
    }

}
