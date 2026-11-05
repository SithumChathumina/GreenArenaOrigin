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
