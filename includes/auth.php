<?php
// Login checks. The user id is saved in the session when the user logs in.

// Get the logged-in user's row from the database (or null if nobody is logged in)
function current_user()
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $user = fetch_one('SELECT id, student_id, full_name, email, role FROM users WHERE id = ?', [$_SESSION['user_id']]);
    if (!$user) {
        // the account no longer exists
        $_SESSION = [];
        return null;
    }
    return $user;
}

function is_logged_in()
{
    return current_user() !== null;
}

function is_admin()
{
    $user = current_user();
    return $user !== null && $user['role'] === 'admin';
}

// Pages for any logged-in user
function require_login()
{
    if (!is_logged_in()) {
        flash('warning', 'Please log in to continue.');
        redirect('login.php');
    }
}

// Pages for students only (admins are sent to the admin panel)
function require_student()
{
    require_login();
    if (is_admin()) {
        redirect('admin/index.php');
    }
}

// Pages for admins only (students are sent back to their dashboard)
function require_admin()
{
    require_login();
    if (!is_admin()) {
        flash('danger', 'You do not have permission to access the admin panel.');
        redirect('student/dashboard.php');
    }
}
