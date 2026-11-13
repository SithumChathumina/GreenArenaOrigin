<?php
function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES);
}


function url($path = '')
{
    return BASE_PATH . '/' . ltrim($path, '/');
}


function asset($path)
{
    return url('assets/' . ltrim($path, '/'));
}

function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}


function run_query($sql, $params = [])
{
    global $connection;

    $statement = mysqli_prepare($connection, $sql);
    if (!$statement) {
        die('SQL error: ' . mysqli_error($connection));
    }

    if (count($params) > 0) {
       
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($statement, $types, ...$params);
    }

    if (!mysqli_stmt_execute($statement)) {
        die('SQL error: ' . mysqli_stmt_error($statement));
    }
    return $statement;
}


function fetch_all($sql, $params = [])
{
    $statement = run_query($sql, $params);
    $result = mysqli_stmt_get_result($statement);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function fetch_one($sql, $params = [])
{
    $rows = fetch_all($sql, $params);
    if (count($rows) > 0) {
        return $rows[0];
    }
    return false;
}


function flash($type, $message)
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flashes()
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}


function format_date($date, $format = 'd M Y')
{
    if ($date) {
        return date($format, strtotime($date));
    }
    return 'Not set';
}

function format_time($time)
{
    if ($time) {
        return date('g:i A', strtotime($time));
    }
    return 'Not set';
}


function status_badge($status)
{
    $classes = [
        'approved' => 'success',
        'active' => 'success',
        'completed' => 'success',
        'registered' => 'success',
        'ongoing' => 'info',
        'scheduled' => 'info',
        'pending' => 'warning',
        'upcoming' => 'warning',
        'rejected' => 'danger',
        'cancelled' => 'danger',
        'maintenance' => 'danger',
        'inactive' => 'secondary',
        'withdrawn' => 'secondary',
    ];
    $class = $classes[$status] ?? 'secondary';
    return '<span class="badge text-bg-' . $class . '">' . e(ucfirst($status)) . '</span>';
}


function active_class($page, $activePage)
{
    if ($page === $activePage) {
        return 'active';
    }
    return '';
}


// ---------- Notifications ----------
// Each notification belongs to one user and is stored in the notifications table.

// true if the notifications table exists (so a missing table never breaks a page)
function notifications_ready()
{
    global $connection;
    static $ready = null;
    if ($ready === null) {
        $result = mysqli_query($connection, "SHOW TABLES LIKE 'notifications'");
        $ready = $result && mysqli_num_rows($result) > 0;
    }
    return $ready;
}

// add one notification for one user. $link is a page inside the site, for example 'student/bookings.php'
function notify($userId, $type, $message, $link = null)
{
    if (!notifications_ready()) {
        return;
    }
    run_query('INSERT INTO notifications (user_id, type, message, link) VALUES (?, ?, ?, ?)', [
        (int) $userId,
        $type,
        mb_substr($message, 0, 255),
        $link,
    ]);
}

// send the same notification to every admin
function notify_admins($type, $message, $link = null)
{
    if (!notifications_ready()) {
        return;
    }
    $admins = fetch_all("SELECT id FROM users WHERE role = 'admin'");
    foreach ($admins as $admin) {
        notify($admin['id'], $type, $message, $link);
    }
}

// send the same notification to every approved member of a club
function notify_club_members($clubId, $type, $message, $link = null)
{
    if (!notifications_ready()) {
        return;
    }
    $members = fetch_all("SELECT user_id FROM memberships WHERE club_id = ? AND status = 'approved'", [$clubId]);
    foreach ($members as $member) {
        notify($member['user_id'], $type, $message, $link);
    }
}

// how many unread notifications a user has (shown on the bell)
function unread_count($userId)
{
    if (!notifications_ready()) {
        return 0;
    }
    return (int) fetch_one('SELECT COUNT(*) AS total FROM notifications WHERE user_id = ? AND is_read = 0', [$userId])['total'];
}
