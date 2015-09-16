<?php

/**
* If you need to move a multisite to another domain, this code can come in handy.
*/

/**
 * DO NOT ADD THIS FILE TO A LIVE SERVER.
 */

/**
 * 1. Run this script
 *   - If it can not be run, change column "domain" in table "wp_blogs" using phpMyAdmin or something similar.
 *      The new value should be the same as the one you are trying to set in this script.
 *    - Then run this script
 * 2. Update DOMAIN_CURRENT_SITE in wp-config.php to the same value as $new below.
 * 3. Edit hosts-file (or use mamp) to have the server name set in $new to point to the wp-install
 */

// Echo the server name. This wil prolly be the value you want for new below.
//die($_SERVER['SERVER_NAME']);

require_once('wp-config.php');

if(WP_ENV !== 'development') {
    die('You did not just try running this on a live site. Did you?!');
}

$mysqlcon = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);

mysqli_select_db($mysqlcon, DB_NAME);

$new = '192.168.0.107';
//$new = '192.168.0.90';

mysqli_query($mysqlcon, 'UPDATE wp_options SET option_value = "http://' . $new . '" WHERE option_name = "siteurl"');
mysqli_query($mysqlcon, 'UPDATE wp_options SET option_value = "http://' . $new . '" WHERE option_name = "home"');

mysqli_query($mysqlcon, 'UPDATE wp_blogs SET domain = "' . $new . '"');

mysqli_query($mysqlcon, 'UPDATE wp_site SET domain = "' . $new . '"');

mysqli_query($mysqlcon, 'UPDATE wp_sitemeta SET meta_value = "http://' . $new . '/" WHERE meta_key = "siteurl"');
