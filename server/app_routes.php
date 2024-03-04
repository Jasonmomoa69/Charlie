<?php

// use Server\Others\Middlewares\UserLoggedIn;
// use Server\Others\Middlewares\AdminLoggedIn;
// use Server\Others\Middlewares\UserOrAdminLoggedIn;

$user_logged_in = new Server\Routes\Middlewares\UserLoggedIn;
$admin_logged_in = new Server\Routes\Middlewares\AdminLoggedIn;
$user_or_admin_logged_in = new Server\Routes\Middlewares\UserOrAdminLoggedIn;

require_once('Routes/admins_auth_routes.php');
require_once('Routes/users_auth_routes.php');
require_once('Routes/users_routes.php');

require_once('Routes/directions_routes.php');
require_once('Routes/wallets_routes.php');
require_once('Routes/links_routes.php');

require_once('Routes/deposits_routes.php');
require_once('Routes/withdrawals_routes.php');
require_once('Routes/approvals_routes.php');
require_once('Routes/user_wallets_routes.php');

require_once('Routes/traders_routes.php');
require_once('Routes/trades_routes.php');
require_once('Routes/trader_user_routes.php');

require_once('Routes/contracts_routes.php');
require_once('Routes/stakes_routes.php');

require_once('Routes/collections_routes.php');
require_once('Routes/categories_routes.php');
require_once('Routes/nfts_routes.php');

require_once('Routes/settings_routes.php');
require_once('Routes/reviews_routes.php');
require_once('Routes/payouts_routes.php');
require_once('Routes/staffs_routes.php');
require_once('Routes/plans_routes.php');
require_once('Routes/pages_routes.php');

require_once('Routes/cms_routes.php');
