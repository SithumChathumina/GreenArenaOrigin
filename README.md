# GreenArena (NSBM Sports Club Management System)

Web and Mobile Application Development - final project, Topic 3.
Built with HTML, CSS, JavaScript, PHP and MySQL.

## Team

| Member | Name | Part |
|---|---|---|
| 1 | Sithum | Home page, styles, script and shared files (leader) |
| 2 | Dineth | Login, register and clubs pages |
| 3 | Vishva | Student dashboard, schedule, history and results |
| 4 | Venuki | Facility booking and registrations |
| 5 | Kaveesha | Admin dashboard, clubs, memberships and training |
| 6 | Risinu | Admin tournaments, matches, results and reports |

## Notifications
Students and admins get in-site notifications (bell icon in the menu, page `notifications.php`).
Examples: a membership or booking is approved or rejected, a match result is recorded, a training session is added.
Notifications can only be marked as read. If you already have a database, add the new table with `database/add_notifications.sql`.

## How to run it
1. Copy the folder into `C:\xampp\htdocs\` and start Apache and MySQL in XAMPP
2. Open phpMyAdmin and import `database/sportshub.sql`
3. Open http://localhost/GreenArenaOrigin/

Demo accounts: admin@nsbm.lk / Admin@123 and student@nsbm.ac.lk / Student@123
