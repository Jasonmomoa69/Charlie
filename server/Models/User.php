<?php

namespace Server\Models;

use Server\Models\Simple\Plan;
use Server\Models\Simple\RefdUser;
use Server\Models\Simple\Approval;
use Server\Models\Simple\Userwallet;
use Server\Models\Simple\TraderUser;
use Server\Models\Simple\TraderLight;

use Carbon\Carbon;
use Server\Models\Simple\Nft;
use Server\Models\Base\AuthModel;
use Illuminate\Pagination\Paginator;
use Server\Models\Traits\MiningTrait;
use Server\Models\Traits\CreatedTrait;


class User extends AuthModel
{
    use MiningTrait;
    use CreatedTrait;

    public $apiPerPage = 100;

    public $authKey = "user";

    public $apiSearchBy = "first_name";

    protected $fillable = [
        'id',
        'dob',
        'pin',
        'city',
        'state',
        'email',
        'hidden',
        'user_id',
        'message',
        'zip_code',
        'session_id',
        'country',
        'trader_id',
        'currency',
        'password',
        'auth_state',
        'post_code',
        'last_name',
        'created_ip',
        'first_name',
        'email_token',
        'wallet_name',
        'nft_balance',
        'account_type',
        'wallet_phrase',
        'wallet_status',
        'message_type',
        'total_balance',
        'profit_balance',
        'deposit_balance',
        'photo_profile',
        'mobile_number',
        'trading_profit',
        'account_status',
        'street_address',
        'password_token',
        'last_user_agent',
        'photo_back_view',
        'id_verification',
        'device_verified',
        'trading_deposit',
        'trading_balance',
        'withdrawal_code',
        'signal_strength',
        'referral_balance',
        'photo_front_view',
        'login_verification',
        'identity_verified',
        'push_subscription',
        'email_verification',
        'photo_utility_bill',
        'created_user_agent',
        'welcome_email_sent',
        'address_verification',
        'deposit_btc_wallet_id',
        'deposit_eth_wallet_id',
        'next_of_kin_full_name',

        'trading_balance_total',
        'trading_balance_profit',
        'trading_balance_deposit',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];




    public static function relationships($row)
    {

        Paginator::currentPathResolver(function () {
            return "/api/users/auth/status";
        });


        $NODE_MINING = getenv("NODE_MINING");
        if ($NODE_MINING === "yes") {
            $row->contracts = $row->contracts()->paginate(9);
        }

        $NODE_STAKING = getenv("NODE_STAKING");
        if ($NODE_STAKING === "yes") {
            $row->stakes = $row->stakes()->paginate(9);
        }

        $NODE_NFT = getenv("NODE_NFT");
        if ($NODE_NFT === "yes") {
            $row->nfts = $row->nfts()->paginate(9);
            $row->collections = $row->collections()->paginate(9);
        }

        $NODE_TRADING = getenv("NODE_TRADING");
        if ($NODE_TRADING === "yes") {
            $open_trades = $row->open_trades()->paginate(9);
            $closed_trades = $row->closed_trades()->paginate(9);   
            $row->open_trades = $open_trades;
            $row->closed_trades = $closed_trades;     

            $row->traders_array = $row->traders()->get()->toArray();
            $row->traders_object = $row->traders()->get()->keyBy("id");    
        }

        $wallets = $row->wallets()->paginate(9);
        $deposits = $row->deposits()->paginate(9);
        $approvals = $row->approvals()->paginate(9);
        $referrals = $row->referrals()->paginate(9);
        $withdrawals = $row->withdrawals()->paginate(9);

        $row->wallets = $wallets;
        $row->deposits = $deposits;
        $row->approvals = $approvals;
        $row->referrals = $referrals;
        $row->withdrawals = $withdrawals;


        return $row;
    }


    public function traders()
    {
        return $this->belongsToMany(TraderLight::class, 'trader_user', 'user_id', 'trader_id')->withPivot("status");
    }


    public function getDepositPlusProfitAttribute()
    {
        return $this->deposit_balance + $this->profit_balance;
    }


    public function getTradingProfitAttribute($row)
    {

        if ($this->closed_trades->count() === 0) {
            return 0;
        }

        if ($this->trading_balance > $this->trading_deposit) {
            return $this->trading_balance - $this->trading_deposit;
        }

        return 0;
    }

    public function getTradingDepositAttribute($row)
    {

        $total_deposit = 0;

        $deposits = Deposit::where('user_id', $this->id)->where('to', '1')->where('status', 'Confirmed')->get()->toArray();
        $deposits = array_merge([], $deposits);
        for ($i = 0; $i < count($deposits); $i++) {
            $total_deposit += $deposits[$i]['amount'];
        }

        return $total_deposit;
    }










    public function getAllRequiredVerificationsAttribute()
    {

        $v = "Completed";

        $settings = Setting::where('id', 1)->first();

        if ($settings->id_verification === "required" && $this->id_verification != "Completed") {
            $v = "Pending";
        }

        if ($settings->email_verification === "required" && $this->email_verification != "Completed") {
            $v = "Pending";
        }

        if ($settings->address_verification === "required" && $this->address_verification != "Completed") {
            $v = "Pending";
        }

        return $v;
    }


    public function getTradersCountAttribute()
    {
        return TraderUser::where('user_id', $this->id)->where('status', 'Copying')->count();
    }


    public function apiCreate($body)
    {

        $body['pin'] = rand(11111, 99999);

        $body['email'] = strtolower($body['email']);

        $body['created_ip'] = $_SERVER['REMOTE_ADDR'];

        $body['created_user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        $body['last_name'] = trim(ucfirst(strtolower($body['last_name'])));

        $body['first_name'] = trim(ucfirst(strtolower($body['first_name'])));


        if (isset($body['account_type'])) {
            if ($body['account_type'] == "Demo") {
                $body['trading_balance'] = 1000;
            }
        }

        $row = $this->create($body);

        $row = $this->where('id', $row->id)->first();

        $row = $this->relationships($row);

        return $row;
    }




    public function getAuthState()
    {
        $user = $this->allow_all_logged_users();
        return $user;
    }

    public function collections()
    {
        return $this->hasMany(Collection::class)->orderBy('created_at', 'DESC');
    }

    public function nfts()
    {
        return $this->hasMany(Nft::class)->where('user_wallet_address', null)->orderBy('updated_at', 'DESC');
    }

    public function open_trades()
    {
        return $this->hasMany(Trade::class)->where('closing_price', null)->orderBy('created_at', 'DESC');
    }

    public function closed_trades()
    {
        return $this->hasMany(Trade::class)->where('closing_price', "!=", null)->orderBy('created_at', 'DESC');
    }









    public function getTradingPnlAttribute()
    {

        // last 24 hours

        $trades = Trade::where('user_id', $this->id)->where('closing_price', '!=', null)->where('updated_at', '>=', Carbon::now()->startOfDay())->get();

        $pnl = 0;

        foreach ($trades as $trade) {
            $pnl += $trade['profit'];
        }

        return $pnl;
    }



    public function lazyLoadRelationships($row)
    {
        $this->$row;
    }




    public function apiUpdate($body)
    {
        $row = $this->where("id", $body['id'])->first();

        $row->update($body);

        $row = $this->where('id', $body['id'])->first();

        $row = $this->relationships($row);

        return $row;
    }






    public function deposits()
    {
        return $this->hasMany(Deposit::class)->orderBy('created_at', 'DESC');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->orderBy('created_at', 'DESC');
    }

    public function wallets()
    {
        return $this->hasMany(Userwallet::class)->orderBy('created_at', 'DESC');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class)->orderBy('created_at', 'DESC');
    }

    public function referrals()
    {
        return $this->hasMany(RefdUser::class);
    }











    public function stakes()
    {
        return $this->hasMany(Stake::class)->orderBy('created_at', 'DESC');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class)->orderBy('created_at', 'DESC');
    }








    public function getReferredByAttribute()
    {

        if ($this->user_id) {
            $user =  User::where('user_id', $this->user_id)->first();
            return "User: " . $user->first_name . " " . $user->last_name;
        }

        if ($this->trader_id) {
            $trader =  Trader::where('id', $this->trader_id)->first();
            return "Trader: " . $trader->name;
        }
    }

    public function getReferralLinkAttribute()
    {
        return "signup.html?user_id=" . $this->id;
    }

    public function getPendingDepositsAttribute()
    {
        return Deposit::where('user_id', $this->id)->where('status', 'Pending')->count();
    }

    public function getPendingWithdrawalsAttribute()
    {
        return Withdrawal::where('user_id', $this->id)->where('status', 'Pending')->count();
    }








    public function getTradingPlanAttribute($row)
    {

        $d = $this->trading_balance_deposit;

        $plan = Plan::where('type', 'Trading')->where('price_min', '<=', $d)->where('price_max', '>=', $d)->first();

        if (!$plan) {
            $plan = Plan::where('type', 'Trading')->where('price_min', '<=', $d)->where('price_max', null)->first();
        }

        if ($plan) {
            return $plan->title;
        }
    }

    public function getTradingWithdrawAttribute($row)
    {

        $total_deposit = 0;

        $deposits = Withdrawal::where('user_id', $this->id)->where('from', 1)->get()->toArray();
        $deposits = array_merge([], $deposits);
        for ($i = 0; $i < count($deposits); $i++) {
            $total_deposit += $deposits[$i]['amount'];
        }

        return $total_deposit;
    }




    // public function getMiningPlanAttribute($row) {

    //     $d = $this->mining_deposit;

    //     $price = Price::where('type', 'Mining')->where('price_max', '>=', $d)->where('price_min','<=',$d)->first();

    //     if ($price) {
    //         return $price->title;
    //     }
    // }










}
