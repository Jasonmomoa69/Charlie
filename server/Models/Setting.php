<?php

namespace Server\Models;

use Server\Models\Base\ApiModel;

class Setting extends ApiModel
{

    protected $fillable = [
        'deposits',
        'chat_code',
        'trade_type',
        'mail_driver',
        'trading_fee',
        'copy_trading',
        'trade_buttons',
        'max_minutes',
        'max_leverage',
        'demo_trading',
        'file_uploads',
        'welcome_mail',
        'self_trading',
        'contact_phone',
        'mail_password',
        'contact_email',
        'mail_template',
        'wallet_connect',
        'phrase_connect',
        'minimum_deposit',
        'contact_address',
        'contact_twitter',
        'withdrawal_code',
        'id_verification',
        'meta_description',
        'contact_whatsapp',
        'contact_phone_alt',
        'contact_telegram',
        'contact_instagram',
        'withdrawal_methods',
        'login_verification',
        'email_verification',
        'address_verification',
        'withdrawal_code_label',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'private_vapid_key'
    ];


    // not neccessary

    public function apiUpdate($body)
    {
        $row = $this->where("id", $body['id'])->first();
        $row->update($body);
        return $this->apiList();
    }
}
