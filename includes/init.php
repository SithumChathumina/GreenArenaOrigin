<?php


session_start();


$folder = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

if (substr($folder, -6) === '/admin') {
    $folder = substr($folder, 0, -6);
} elseif (substr($folder, -8) === '/student') {
    $folder = substr($folder, 0, -8);
}
if ($folder === '/' || $folder === '.') {
    $folder = '';
}
define('BASE_PATH', $folder);

date_default_timezone_set('Asia/Colombo');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
