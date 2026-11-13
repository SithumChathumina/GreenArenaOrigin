-- OPTIONAL: adds a few sample notifications for the demo accounts, without deleting anything.
-- Run it in phpMyAdmin (SQL tab) AFTER database/add_notifications.sql.
-- Safe to skip: real notifications are created by using the site.
-- It looks the users up by email, so it does nothing if those demo accounts do not exist.

INSERT INTO notifications (user_id, type, message, link, is_read)
SELECT id, 'membership', 'Your request to join NSBM Cricket Club was approved.', 'club.php?id=1', 0
FROM users WHERE email = 'student@nsbm.ac.lk';

INSERT INTO notifications (user_id, type, message, link, is_read)
SELECT id, 'booking', 'Your booking for Basketball was approved.', 'student/bookings.php', 0
FROM users WHERE email = 'student@nsbm.ac.lk';

INSERT INTO notifications (user_id, type, message, link, is_read)
SELECT id, 'booking', 'Demo Student requested Indoor Court 1.', 'admin/bookings.php', 0
FROM users WHERE email = 'admin@nsbm.lk';
