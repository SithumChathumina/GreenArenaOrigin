<?php
// Login page for students and admins
require_once __DIR__ . '/includes/init.php';

if (is_logged_in()) {
    if (is_admin()) {
        redirect('admin/index.php');
    }
    redirect('student/dashboard.php');
}

// title and menu highlight used by header.php

$pageTitle = 'Log in';
$activePage = 'login';
$error = '';
$email = '';

// this part runs when a form on the page was submitted

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Please enter a valid email and your password.';
    } else {
        // find the user with this email
        $user = fetch_one('SELECT * FROM users WHERE email = ?', [$email]);

        // password_verify compares the typed password with the saved hash
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            flash('success', 'Welcome back, ' . $user['full_name'] . '!');
            if ($user['role'] === 'admin') {
                redirect('admin/index.php');
            }
            redirect('student/dashboard.php');
        }
        $error = 'Invalid email or password.';
    }
}

// show the top of the page

include __DIR__ . '/includes/header.php';
?>
<section class="auth-section">
    <div class="container">
        <div class="auth-layout">
            <div class="auth-intro">
                <span class="hero-kicker">Welcome back</span>
                <h1>Ready for the next session?</h1>
                <p>Log in to manage your clubs, registrations and bookings. Administrators can use the same form to reach the admin panel.</p>
                <div class="demo-note mt-4"><strong>Demo student</strong><br>student@nsbm.ac.lk<br>Student@123<br><br><strong>Demo admin</strong><br>admin@nsbm.lk<br>Admin@123
                </div>
            </div>
            <div class="auth-form">
                <h2 class="mb-4">Log in to NSBM Green Arena</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert"><?= e($error) ?>
                </div><?php endif; ?>
                <form method="post" class="needs-validation" novalidate>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" autocomplete="email" required>
                        <div class="invalid-feedback">Enter a valid email address.</div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                        <div class="invalid-feedback">Enter your password.</div>
                    </div>
                    <button type="submit" class="btn btn-sport w-100">Log in</button>
                </form>
                <p class="mt-4 mb-0">New to NSBM GreenArena? <a href="<?= e(url('register.php')) ?>">Create a student account</a>.</p>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
