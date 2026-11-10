<?php
// Contact page: anyone can send a message to the admin
require_once __DIR__ . '/includes/init.php';

// title and menu highlight used by header.php
$pageTitle = 'Contact the admin';
$activePage = 'contact';
$errors = [];
$values = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

// if the visitor is logged in, fill in their name and email for them
$loggedIn = current_user();
if ($loggedIn) {
    $values['name'] = $loggedIn['full_name'];
    $values['email'] = $loggedIn['email'];
}

// this part runs when the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['name'] = trim($_POST['name'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $values['subject'] = trim($_POST['subject'] ?? '');
    $values['message'] = trim($_POST['message'] ?? '');

    // this box is hidden from people, so only spam bots fill it in
    if (($_POST['website'] ?? '') !== '') {
        redirect('contact.php');
    }

    if (strlen($values['name']) < 2 || strlen($values['name']) > 100) {
        $errors[] = 'Enter your name (2–100 characters).';
    }
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }
    if (strlen($values['subject']) < 3 || strlen($values['subject']) > 150) {
        $errors[] = 'Enter a subject (3–150 characters).';
    }
    if (strlen($values['message']) < 10 || strlen($values['message']) > 2000) {
        $errors[] = 'Write your message (10–2000 characters).';
    }

    // save the message so the admin can read it in the admin panel
    if (!$errors) {
        $userId = null;
        if ($loggedIn) {
            $userId = $loggedIn['id'];
        }
        run_query('INSERT INTO messages (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)', [
            $userId,
            $values['name'],
            $values['email'],
            $values['subject'],
            $values['message'],
        ]);
        flash('success', 'Thank you! Your message was sent to the admin.');
        redirect('contact.php');
    }
}

// show the top of the page
include __DIR__ . '/includes/header.php';
?>
<section class="auth-section">
    <div class="container">
        <div class="auth-layout">
            <div class="auth-intro">
                <span class="hero-kicker">Contact</span>
                <h1>Questions? Ask the admin.</h1>
                <p>Send a question about clubs, bookings or your account. Only the GreenArena admin can read your message, and the reply goes to the email you enter.</p>
            </div>
            <div class="auth-form">
                <h2 class="mb-4">Send a message</h2>
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-danger" role="alert"><?= e($error) ?></div>
                <?php endforeach; ?>
                <form method="post" class="needs-validation" novalidate>
                    <div class="d-none">
                        <label for="website">Leave this empty</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Your name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= e($values['name']) ?>" maxlength="100" required>
                        <div class="invalid-feedback">Enter your name.</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= e($values['email']) ?>" required>
                        <div class="invalid-feedback">Enter a valid email address.</div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="<?= e($values['subject']) ?>" maxlength="150" required>
                        <div class="invalid-feedback">Enter a subject.</div>
                    </div>
                    <div class="mb-4">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" maxlength="2000" required><?= e($values['message']) ?></textarea>
                        <div class="invalid-feedback">Write your message (at least 10 characters).</div>
                    </div>
                    <button type="submit" class="btn btn-sport w-100">Send message</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
