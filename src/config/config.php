<?php
require_once __DIR__ . '/env.php';

define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME'));
define('DB_USER', env('DB_USER'));
define('DB_PASSWORD', env('DB_PASSWORD'));

// Never render PHP errors to the browser. It can leak paths, queries, and credentials
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);
