<!DOCTYPE html>
<html>

<head>

    <title>Trades</title>

    <link href="/assets/css/site/reactapp-modules.css" rel="stylesheet" />

    <link href="/universal-finance.css" rel="stylesheet" />


    <script>
        const root = document.querySelector(":root");

        root.style.setProperty("--hover", "#252b3c");

        root.style.setProperty("--nav-primary-font-colour", "white");
        root.style.setProperty("--nav-secondary-font-colour", "white");

        root.style.setProperty("--background-colour", "#000000");
        root.style.setProperty("--background-font-colour", "#a5bdd9");
        root.style.setProperty("--background-heading-colour", "#ffffff");

        root.style.setProperty("--primary-background", "#0b1118");
        root.style.setProperty("--primary-font-colour", "#a5bdd9");
        root.style.setProperty("--primary-border-colour", "#363c4e");
        root.style.setProperty("--primary-link-colour", "#FFFFFF");

        root.style.setProperty("--secondary-background", "#000000");
        root.style.setProperty("--secondary-font-colour", "#a5bdd9");
        root.style.setProperty("--secondary-heading-colour", "#ffffff");

        root.style.setProperty("--border-colour", "#434651");
    </script>
</head>

<body>

    <br /><br />

    <section class="container">

        <ul class="collection">

            <?php

            require_once('../vendor/autoload.php');

            use Server\Models\User;
            use Server\Models\Trade;
            use Server\Others\Connection;
            use Server\Models\TradingBalanceProfit;

            $client = new GuzzleHttp\Client();
            $res = $client->request('GET', 'https://probrokerapis.com/api/prices', ['headers' => ['origin' => 'https://' . $_SERVER['HTTP_HOST']]]);
            $prices = (array) json_decode($res->getBody());

            new Connection;
            $time = time();
            $trades = Trade::where('closing_price', null)->orderBy('created_at', 'DESC')->get()->toArray();

            foreach ($trades as $trade) {
                

                $type = $trade['type'];
                
                $amount = $trade['amount'];
                
                $opening_price = $trade['opening_price'];

                $closing_price = $prices[$trade['symbol']];

                if ($type == "BUY" || $type == "HIGHER" || $type == "CALL" || $type == "UP") {
                    $profit = $closing_price - $opening_price;
                }

                if ($type == "SELL" || $type == "LOWER" || $type == "PUT" || $type == "DOWN") {
                    $profit = $opening_price - $closing_price;
                }

                $profit = $profit * $amount;




                if ($trade['time'] != NULL) {

                    // calculate time allowed

                    $destination_minutes = $trade['time'];

                    $destination_seconds = $destination_minutes * 60;

                    $destination_hours = $destination_minutes / 60;

                    $destination_days = $destination_hours / 24;



                    // calculate time past
                    $current_timestamp = time();

                    $open_timestamp = $trade['php_timestamp'];

                    $seconds_past = $current_timestamp - $open_timestamp;

                    $minutes_past = $seconds_past / 60;

                    $hours_past = $minutes_past / 60;

                    $days_past = $hours_past / 24;



                    // calculate time remaining
                    $seconds = $destination_seconds - $seconds_past;

                    $minutes = $destination_minutes - $minutes_past;

                    $hours = $destination_hours - $hours_past;

                    $days = $destination_days - $days_past;



                    $sc = $seconds % 60;
                    $mc = $minutes % 60;
                    $hc = $hours % 24;
                    $dc = $days % 365;


                    $duration = $trade['time'];
                    $current_time = new DateTime();
                    $created_time = (new DateTime())->setTimeStamp($trade['php_timestamp']);
                    $time_difference = $current_time->diff($created_time);
                    $minutes_left = $duration - 1 - $time_difference->i;
                    $seconds_left = 60 - $time_difference->s;






                    if ($seconds <= 0) {
                        $closingTrade = Trade::where("id", $trade['id'])->first();

                        $body = [];
                        $body['profit'] = $profit;
                        $body['closing_price'] = $closing_price;

                        if ($closingTrade->profit != NULL) {
                            $body['profit'] = $closingTrade->profit;
                        }

                        $closingTrade->update($body);


                        $user = User::where('id', $closingTrade->user_id)->first();

                        // handle loss
                        if ($body["profit"] < 0) {
                            TradingBalanceProfit::addNegativeValue($user, $body["profit"]);
                        }

                        // handle profit
                        if ($body['profit'] >= 0) {
                            TradingBalanceProfit::addPositiveValue($user, $body["profit"]);
                        }

                    }


                    echo "
                    <li class='collection-item'> 
                        <div class='row'>
                            <div class='col l10 s10'>
                                {$type} {$amount} {$trade['symbol']}<br/>
                                {$trade['opening_price']} - {$closing_price}  
                            </div>
                            <div class='col l2 s2'>
                                ({$dc}:{$hc}:{$mc}:{$sc})
                                {$profit}  
                            </div>
                        </div>
                    </li>
                    ";

                    return;
                }

    




                echo "
                <li class='collection-item'> 
                    <div class='row'>
                        <div class='col l10 s10'>
                            {$type} {$amount} {$trade['symbol']}<br/>
                            {$trade['opening_price']} - {$closing_price}  
                        </div>
                        <div class='col l2 s2'>
                            {$profit}  
                        </div>
                    </div>
                </li>
                ";


            };
            ?>
        </ul>

    </section>
</body>