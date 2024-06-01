<?php
// DB MYSQL SETTING !IMPORTANT

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'Manuel21051986_33!');
define('DB_DATA', 'Uniques');
define('DB_PORT', 3306);
define('LICENSE_KEY', 'BPZ0I-T4FEF-BBY5K-61AJC-R6CRY');

define('SITE_NAME', 'Unique');
define('IMAGE_LOGO', 'uniquelogo.png'); //static/img/ put your logo.
define('CKAP_KEY', 'cb66a38728b6e69c85523921f8b49826'); // Java code KEY for using your system

// NAVBAR CONFIGURATION
$icon_navbar = true; // FOR VIEW ICONS IN NAVBAR

// LICENSE GENERATE KEY - CONFIGURE
$length_key = 5; // 123456-ETC..
$type_letter = true; // true : capital letter - false : lower case
$large_key = 4; // 123-123-123-123-ETC.. - MAX: 6

// CONFIGURE YOU DISCORD LOGIN
// https://discord.com/developers/applications
// CREATE ONE APPLICATION, OAuth2 > Redirects set your $redirect_uri = '', And copy client secret and client id.
$client_id = '1144072683714269334';
$client_secret = 'k1DMeTk9kdgNxYBqP-VANL3Loqczu7py';
$redirect_uri = 'https://vuhp.vanityproyect.fun/UniqueOLD'; // Example: https://your-site.com/unique

// WEBHOOKS
$log_webhook = '';
$license_new = '';

// GIVE RANK ON YOUR SERVER OF DISCORD
// REQUIRE BOT IN YOUR SERVER (NO REQUIRE BOT ACTIVE/ONLINE)
$bot_token = ''; // BOT TOKEN
$guild_id = ''; // SERVER ID
$customer_id = ''; // ROLE ID OF CUSTOMER
?>