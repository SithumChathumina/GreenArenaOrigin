<?php
// This file is included at the top of every page.
// It starts the session and loads the database and the shared functions.

session_start();

// BASE_PATH is the folder name of the site in the web address, for example /sportshub3.3
// It is worked out from the address of the page that is running, so the folder can have
// any name and the links and redirects always stay inside it.
// On hosting where the site is in the main folder, BASE_PATH is empty.
$folder = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// pages inside the admin and student folders are one level deeper, so remove that part
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
