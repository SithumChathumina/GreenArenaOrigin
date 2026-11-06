<?php
// Log the user out
require_once __DIR__ . '/includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

// Remove everything stored in the session, Then start a fresh one for the message
$_SESSION = [];
session_destroy();
session_start();
flash('success', 'You have been logged out.');
redirect('index.php');
