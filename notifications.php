<?php
// Notifications page: every logged-in user sees their own notifications and can mark them as read
require_once __DIR__ . '/includes/init.php';
// any logged-in user (student or admin) can open this page
require_login();
$userId = current_user()['id'];

// open one notification: mark it as read, then go to the page it points to
if (isset($_GET['open'])) {
    $notification = fetch_one('SELECT * FROM notifications WHERE id = ? AND user_id = ?', [(int) $_GET['open'], $userId]);
    if ($notification) {
        run_query('UPDATE notifications SET is_read = 1 WHERE id = ?', [$notification['id']]);
        if ($notification['link']) {
            redirect($notification['link']);
        }
    }
    redirect('notifications.php');
}

// this part runs when a button on the page was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'read') {
        // the "AND user_id" part means nobody can change another person's notifications
        run_query('UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?', [(int) ($_POST['id'] ?? 0), $userId]);
        flash('success', 'Notification marked as read.');
    } elseif ($action === 'read_all') {
        run_query('UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0', [$userId]);
        flash('success', 'All notifications marked as read.');
    }
    redirect('notifications.php');
}

// title and menu highlight used by header.php
$pageTitle = 'Notifications';
$activePage = 'notifications';
$unread = unread_count($userId);
$notifications = [];
if (notifications_ready()) {
    // newest first, only the latest 50
    $notifications = fetch_all('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT 50', [$userId]);
}
// show the top of the page
include __DIR__ . '/includes/header.php';
?>
<section class="page-hero">
    <div class="container"><span class="hero-kicker">Your inbox</span>
        <h1>Notifications</h1>
        <p><?= $unread > 0 ? 'You have ' . $unread . ' unread notification' . ($unread === 1 ? '' : 's') . '.' : 'You are all caught up.' ?></p>
    </div>
</section>
<section class="page-content">
    <div class="container dashboard-content">
        <section class="content-panel">
            <?php if ($notifications): ?>
                <?php if ($unread > 0): ?>
                    <form method="post" class="mb-3">
                        <input type="hidden" name="action" value="read_all">
                        <button class="btn btn-sm btn-outline-sport" type="submit">Mark all as read</button>
                    </form>
                <?php endif; ?>
                <ul class="notification-list">
                    <?php foreach ($notifications as $item): ?>
                        <li class="notification-item <?= $item['is_read'] ? '' : 'unread' ?>">
                            <div class="notification-body">
                                <span class="notification-type"><?= e(ucfirst($item['type'])) ?></span>
                                <?php if ($item['link']): ?>
                                    <a class="notification-text" href="<?= e(url('notifications.php?open=' . $item['id'])) ?>"><?= e($item['message']) ?></a>
                                <?php else: ?>
                                    <span class="notification-text"><?= e($item['message']) ?></span>
                                <?php endif; ?>
                                <small class="notification-time"><?= format_date($item['created_at'], 'd M Y, g:i A') ?></small>
                            </div>
                            <?php if (!$item['is_read']): ?>
                                <form method="post">
                                    <input type="hidden" name="action" value="read">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button class="btn btn-sm btn-sport" type="submit">Mark as read</button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="mb-0">No notifications yet. They will appear here when something changes on your account.</p>
            <?php endif; ?>
        </section>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
