<?php
// ERROR REPORTING
error_reporting(E_ALL & ~E_NOTICE);

// DATABASE
define('DB_HOST', 'localhost');
define('DB_NAME', 'radius');
define('DB_CHARSET', 'utf8');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');

$time_now=time();
$admin_time=21600; // Time to recognize ip address of admin(second)
$fail_wait=300; // Waiting time for enable login(second)
$fail_allow=3; // Amount of try to login

?>
