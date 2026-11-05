<?php

function current_user()
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $user = fetch_one('SELECT id, student_id, full_name, email, role FROM users WHERE id = ?', [$_SESSION['user_id']]);
    if (!$user) {
        
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

function require_student()// Pages for students 
{
    require_login();
    if (is_admin()) {
        redirect('admin/index.php');
    }
}

function require_admin()     // Pages for admins 
{
    require_login();
    if (!is_admin()) {
        flash('danger', 'You do not have permission to access the admin panel.');
        redirect('student/dashboard.php');
    }
}
