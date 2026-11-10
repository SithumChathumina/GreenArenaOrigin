<?php
// Admin: read the messages sent from the contact page
require_once __DIR__ . '/../includes/init.php';
// only admins are allowed on this page
require_admin();

// this part runs when a button on the page was clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($action === 'read') {
        run_query("UPDATE messages SET status='read' WHERE id=?", [$id]);
        flash('success', 'Message marked as read.');
    } elseif ($action === 'unread') {
        run_query("UPDATE messages SET status='new' WHERE id=?", [$id]);
        flash('success', 'Message marked as new.');
    } elseif ($action === 'delete') {
        run_query('DELETE FROM messages WHERE id=?', [$id]);
        flash('success', 'Message deleted.');
    }
    redirect('admin/messages.php');
}

// title and menu highlight used by header.php
$pageTitle = 'Messages';
$activePage = 'dashboard';
$adminSection = 'messages';

// optional filter: all, new or read
$status = $_GET['status'] ?? '';
$params = [];
$where = '';
if ($status === 'new' || $status === 'read') {
    $where = 'WHERE status = ?';
    $params[] = $status;
}
$messages = fetch_all("SELECT * FROM messages $where ORDER BY FIELD(status, 'new', 'read'), created_at DESC", $params);

// show the top of the page
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/admin_nav.php';
?>
<div class="container dashboard-head">
    <span class="section-kicker">Inbox</span>
    <h1>Messages</h1>
    <p>Questions sent from the contact page. Only admins can see this page.</p>
</div>
<div class="container dashboard-content">
    <section class="content-panel">
        <form class="filter-bar" method="get">
            <div>
                <label class="visually-hidden" for="status">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All messages</option>
                    <option value="new" <?= $status === 'new' ? 'selected' : '' ?>>New</option>
                    <option value="read" <?= $status === 'read' ? 'selected' : '' ?>>Read</option>
                </select>
            </div>
            <div></div>
            <button class="btn btn-sport" type="submit">Filter messages</button>
        </form>
        <?php if ($messages): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>Message</th>
                            <th>Received</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $row): ?>
                            <tr>
                                <td>
                                    <strong><?= e($row['name']) ?></strong><br>
                                    <small><a href="mailto:<?= e($row['email']) ?>"><?= e($row['email']) ?></a></small>
                                    <?php if ($row['user_id']): ?><br><small>Logged-in member</small><?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= e($row['subject']) ?></strong><br>
                                    <?= nl2br(e($row['message'])) ?>
                                </td>
                                <td><?= format_date($row['created_at']) ?><br><small><?= format_time($row['created_at']) ?></small></td>
                                <td><?= status_badge($row['status'] === 'new' ? 'pending' : 'completed') ?></td>
                                <td>
                                    <div class="table-actions">
                                        <form method="post">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <?php if ($row['status'] === 'new'): ?>
                                                <input type="hidden" name="action" value="read">
                                                <button class="btn btn-sm btn-sport" type="submit">Mark as read</button>
                                            <?php else: ?>
                                                <input type="hidden" name="action" value="unread">
                                                <button class="btn btn-sm btn-outline-sport" type="submit">Mark as new</button>
                                            <?php endif; ?>
                                        </form>
                                        <form method="post">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button class="btn btn-sm btn-outline-danger" type="submit" data-confirm="Delete this message?">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No messages yet.</p>
        <?php endif; ?>
    </section>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
