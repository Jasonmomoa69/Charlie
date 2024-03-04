<?php

namespace Server\Models;

use Server\Models\Base\NewApiModel;
use Server\Models\Simple\TradeLight;
use Server\Models\Simple\TraderUser;
use Server\Others\Services\Uploader;

class Trader extends NewApiModel
{

    public $apiSearchBy = "name";

    protected $fillable = [
        'bio',
        'name',
        'type',
        'price',
        'email',
        'photo',
        'handle',
        'win_rate',
        'min_deposit',
        'wins_offset',
        'description',
        'profit_share',
        'losses_offset',
        'copiers_offset',
        'followers_offset'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];




    public function apiCreate($body)
    {

        $body['losses_offset'] = rand(10, 90);
        $body['wins_offset'] = rand(100, 1000);
        $body['copiers_offset'] = rand(1000, 10000);

        $this->modelResponse['data'] = $this->create($body);

        return $this->modelResponse;
    }



    public function apiUpdate($body)
    {

        if (!isset($body['id'])) {
            $this->modelResponse['errors'] = ['id is required'];
            return $this->modelResponse;
        }

        if (isset($_FILES['photo'])) {

            $uploaderResponse = Uploader::upload('photo');

            if (count($uploaderResponse['errors'])) {

                $this->modelResponse['errors'] = $uploaderResponse['errors'];

                return $this->modelResponse;
            }

            $body['photo'] = $uploaderResponse['fullname'];
        }


        $data = $this->where("id", $body['id'])->first();

        $data->update($body);

        $data = $this->where('id', $body['id'])->first();

        $this->modelResponse['data'] = $data;

        $this->modelResponse['message'] = "Updated";

        return $this->modelResponse;
    }












    public function trades()
    {
        return $this->hasMany(TradeLight::class)->orderBy('created_at', 'DESC');
    }

    public function copiers()
    {
        return $this->belongsToMany(User::class)->withPivot('status');
    }

    public static function relationships($row)
    {
        $copiers = $row->copiers()->paginate(25);
        $row->copiers = $copiers;
        return $row;
    }





























    public function getReferralLinkAttribute()
    {
        return "signup.html?trader_id=" . $this->id;
    }

    public function getPendingRequestsAttribute()
    {
        return TraderUser::where('trader_id',  $this->id)->where('status', 'Requested')->count();
    }


    public function getCopiersCountAttribute()
    {
        return $this->copiers->count();
    }

    public function getTradesCountAttribute()
    {
        return $this->trades->count();
    }

    public function getWinsCountAttribute()
    {
        return $this->trades->where('profit', '>=', 0)->count();
    }

    public function getLossesCountAttribute()
    {
        return $this->trades->where('profit', '<', 0)->count();
    }

    public function getWinPercentAttribute()
    {
        if ($this->trades_count > 0) {
            return number_format(($this->wins_count / $this->trades_count) * 100, 2) . "%";
        }
    }

    public function getTotalWinsAttribute()
    {
        return $this->wins_count + $this->wins_offset;
    }

    public function getTotalCopiersAttribute()
    {
        return $this->copiers_count + $this->copiers_offset;
    }

    public function getTotalLossesAttribute()
    {
        return $this->losses_count + $this->losses_offset;
    }

    public function getTotalTradesAttribute()
    {
        return $this->total_wins + $this->total_losses;
    }

    public function getTotalWinPercentAttribute()
    {
        if ($this->total_trades > 0) {
            return number_format(($this->total_wins / $this->total_trades) * 100, 2) . "%";
        }
    }
}
