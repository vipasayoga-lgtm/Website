<?php

/**
 * Vipasa Yoga — Session Reminder CLI Runner
 * Run via cron/scheduler: php bin/send-reminders.php
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Database;
use App\Core\Mailer;
use App\Models\Notification;

echo sprintf("[%s] Starting Vipasa Yoga session reminder runner...\n", date('Y-m-d H:i:s'));

$db = Database::connection();

// Ensure reminder tracking columns exist
try {
    $db->exec("ALTER TABLE bookings ADD COLUMN reminder_sent_24h TINYINT(1) NOT NULL DEFAULT 0 AFTER reminder_sent_at");
} catch (\Throwable $e) {
    // Column already exists
}

try {
    $db->exec("ALTER TABLE bookings ADD COLUMN reminder_sent_1h TINYINT(1) NOT NULL DEFAULT 0 AFTER reminder_sent_24h");
} catch (\Throwable $e) {
    // Column already exists
}

// -----------------------------------------------------------------------------
// 1. Process 24-Hour Reminders
// -----------------------------------------------------------------------------
$stmt24h = $db->prepare("
    SELECT b.*,
           u.name AS client_name, u.email AS client_email,
           iu.name AS instructor_name, iu.email AS instructor_email,
           pkg.name AS package_name
    FROM bookings b
    JOIN users u ON u.id = b.client_id
    JOIN instructors i ON i.id = b.instructor_id
    JOIN users iu ON iu.id = i.user_id
    JOIN packages pkg ON pkg.id = b.package_id
    WHERE b.status IN ('confirmed', 'awaiting_trainer_approval')
      AND TIMESTAMP(b.slot_date, b.start_time) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR)
      AND (b.reminder_sent_24h = 0)
");

$stmt24h->execute();
$upcoming24h = $stmt24h->fetchAll();
$sent24hCount = 0;

foreach ($upcoming24h as $b) {
    $dateStr = date('F j, Y', strtotime($b['slot_date']));
    $timeStr = date('g:i A', strtotime($b['start_time']));

    $clientSubject = "Reminder: Your Vipasa Yoga session is tomorrow at {$timeStr}";
    $clientBody = "<p>Hi <strong>" . htmlspecialchars($b['client_name']) . "</strong>,</p>"
        . "<p>This is a friendly reminder that your upcoming yoga session <strong>" . htmlspecialchars($b['package_name']) . "</strong> with instructor <strong>" . htmlspecialchars($b['instructor_name']) . "</strong> is scheduled for tomorrow:</p>"
        . "<p><strong>Date:</strong> {$dateStr}<br><strong>Time:</strong> {$timeStr}<br><strong>Ref:</strong> " . htmlspecialchars($b['booking_ref']) . "</p>"
        . "<p><a href=\"/dashboard/bookings\">View your dashboard</a> to join or review session details.</p>";

    Mailer::send($b['client_email'], $clientSubject, $clientBody);

    Notification::notify(
        (int) $b['client_id'],
        'Reminder: Session Tomorrow',
        "Your {$b['package_name']} session with {$b['instructor_name']} is tomorrow at {$timeStr}.",
        "/dashboard/bookings"
    );

    $instSubject = "Reminder: Session scheduled for tomorrow at {$timeStr} with " . htmlspecialchars($b['client_name']);
    $instBody = "<p>Hi <strong>" . htmlspecialchars($b['instructor_name']) . "</strong>,</p>"
        . "<p>Reminder for your upcoming session tomorrow:</p>"
        . "<p><strong>Client:</strong> " . htmlspecialchars($b['client_name']) . "<br><strong>Package:</strong> " . htmlspecialchars($b['package_name']) . "<br><strong>Date:</strong> {$dateStr}<br><strong>Time:</strong> {$timeStr}</p>"
        . "<p><a href=\"/trainer/bookings\">Open Trainer Portal</a></p>";

    Mailer::send($b['instructor_email'], $instSubject, $instBody);

    $upd = $db->prepare("UPDATE bookings SET reminder_sent_24h = 1, reminder_sent_at = NOW() WHERE id = :id");
    $upd->execute(['id' => $b['id']]);

    $sent24hCount++;
}

echo sprintf("[%s] Sent %d 24-hour reminder emails.\n", date('Y-m-d H:i:s'), $sent24hCount);

// -----------------------------------------------------------------------------
// 2. Process 1-Hour Reminders
// -----------------------------------------------------------------------------
$stmt1h = $db->prepare("
    SELECT b.*,
           u.name AS client_name, u.email AS client_email,
           iu.name AS instructor_name, iu.email AS instructor_email,
           pkg.name AS package_name
    FROM bookings b
    JOIN users u ON u.id = b.client_id
    JOIN instructors i ON i.id = b.instructor_id
    JOIN users iu ON iu.id = i.user_id
    JOIN packages pkg ON pkg.id = b.package_id
    WHERE b.status IN ('confirmed', 'awaiting_trainer_approval')
      AND TIMESTAMP(b.slot_date, b.start_time) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 HOUR)
      AND (b.reminder_sent_1h = 0)
");

$stmt1h->execute();
$upcoming1h = $stmt1h->fetchAll();
$sent1hCount = 0;

foreach ($upcoming1h as $b) {
    $timeStr = date('g:i A', strtotime($b['start_time']));

    $clientSubject = "Starting Soon: Your Vipasa Yoga session starts in 1 hour ({$timeStr})";
    $clientBody = "<p>Hi <strong>" . htmlspecialchars($b['client_name']) . "</strong>,</p>"
        . "<p>Your yoga session <strong>" . htmlspecialchars($b['package_name']) . "</strong> starts in approximately 1 hour!</p>"
        . "<p><strong>Time:</strong> {$timeStr}<br><strong>Ref:</strong> " . htmlspecialchars($b['booking_ref']) . "</p>"
        . "<p><a href=\"/booking/" . urlencode($b['booking_ref']) . "/join\">Click here to join your session</a></p>";

    Mailer::send($b['client_email'], $clientSubject, $clientBody);

    Notification::notify(
        (int) $b['client_id'],
        'Session Starting Soon',
        "Your {$b['package_name']} session starts in 1 hour. Get ready!",
        "/booking/" . urlencode($b['booking_ref']) . "/join"
    );

    $instSubject = "Starting Soon: Session in 1 hour with " . htmlspecialchars($b['client_name']);
    $instBody = "<p>Hi <strong>" . htmlspecialchars($b['instructor_name']) . "</strong>,</p>"
        . "<p>Your session with <strong>" . htmlspecialchars($b['client_name']) . "</strong> starts in 1 hour.</p>"
        . "<p><a href=\"/trainer/bookings\">Open Trainer Portal</a></p>";

    Mailer::send($b['instructor_email'], $instSubject, $instBody);

    $upd = $db->prepare("UPDATE bookings SET reminder_sent_1h = 1, reminder_sent_at = NOW() WHERE id = :id");
    $upd->execute(['id' => $b['id']]);

    $sent1hCount++;
}

echo sprintf("[%s] Sent %d 1-hour reminder emails.\n", date('Y-m-d H:i:s'), $sent1hCount);
echo sprintf("[%s] Reminder runner execution completed successfully.\n", date('Y-m-d H:i:s'));
