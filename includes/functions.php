<?php
// Shared helper functions used by many pages.

// Make text safe to print inside HTML.
function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES);
}

// Build a link that works from any folder, e.g. url('login.php')
function url($path = '')
{
    return BASE_PATH . '/' . ltrim($path, '/');
}

// Link to a file inside the assets folder
function asset($path)
{
    return url('assets/' . ltrim($path, '/'));
}

// Send the user to another page and stop running this one
function redirect($path)
{
    header('Location: ' . url($path));
    exit;
}

// ---------- Database helpers ----------
// The values go in separately from the SQL (prepared statement),
// so what the user types can never change the SQL command.

// Run any SQL query. $params is the list of values for the ? marks.
function run_query($sql, $params = [])
{
    global $connection;

    $statement = mysqli_prepare($connection, $sql);
    if (!$statement) {
        die('SQL error: ' . mysqli_error($connection));
    }

    if (count($params) > 0) {
        // 's' means every value is sent as a string (MySQL converts numbers for us)
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($statement, $types, ...$params);
    }

    if (!mysqli_stmt_execute($statement)) {
        die('SQL error: ' . mysqli_stmt_error($statement));
    }
    return $statement;
}

// Get many rows (for SELECT). Returns an array of rows.
function fetch_all($sql, $params = [])
{
    $statement = run_query($sql, $params);
    $result = mysqli_stmt_get_result($statement);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get one row (for SELECT). Returns false if nothing was found.
function fetch_one($sql, $params = [])
{
    $rows = fetch_all($sql, $params);
    if (count($rows) > 0) {
        return $rows[0];
    }
    return false;
}

// ---------- Messages shown at the top of the next page ----------
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

// ---------- Formatting ----------
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

// Coloured label for a status such as pending / approved
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

// Adds "active" to the menu link of the page we are on
function active_class($page, $activePage)
{
    if ($page === $activePage) {
        return 'active';
    }
    return '';
}
