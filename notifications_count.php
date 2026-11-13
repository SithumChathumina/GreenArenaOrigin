<?php
// Tiny page used by main.js: answers with the unread count as JSON so the bell can update itself
require_once __DIR__ . '/includes/init.php';
header('Content-Type: application/json');
$count = 0;
if (is_logged_in()) {
    $count = unread_count(current_user()['id']);
}
echo json_encode(['count' => $count]);
