<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;
use App\Core\Env;

$backupDir = dirname(__DIR__) . '/storage/backups';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$dbName = Env::get('DB_NAME', 'vipasa_yoga');
$timestamp = date('Y-m-d_H-i-s');
$backupFile = $backupDir . "/backup_{$dbName}_{$timestamp}.sql";

echo "Starting automated database backup for '{$dbName}'...\n";

try {
    $db = Database::connection();
    $tables = $db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    $sqlDump = "-- ============================================================\n";
    $sqlDump .= "-- Vipasa Yoga Automated Database Backup\n";
    $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $sqlDump .= "-- Database: {$dbName}\n";
    $sqlDump .= "-- ============================================================\n\n";
    $sqlDump .= "SET FOREIGN_KEY_CHECKS = 0;\n";
    $sqlDump .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n";

    foreach ($tables as $table) {
        // Table structure
        $createStmt = $db->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC);
        $createTableSql = $createStmt['Create Table'] ?? '';

        $sqlDump .= "-- ------------------------------------------------------------\n";
        $sqlDump .= "-- Structure for table `{$table}`\n";
        $sqlDump .= "-- ------------------------------------------------------------\n";
        $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n";
        $sqlDump .= $createTableSql . ";\n\n";

        // Table data
        $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            $sqlDump .= "-- Data for table `{$table}` (" . count($rows) . " rows)\n";
            $sqlDump .= "INSERT INTO `{$table}` VALUES \n";

            $rowSqls = [];
            foreach ($rows as $row) {
                $values = array_map(function ($val) use ($db) {
                    if ($val === null) {
                        return 'NULL';
                    }
                    return $db->quote((string)$val);
                }, array_values($row));
                $rowSqls[] = '(' . implode(', ', $values) . ')';
            }

            $sqlDump .= implode(",\n", $rowSqls) . ";\n\n";
        }
    }

    $sqlDump .= "SET FOREIGN_KEY_CHECKS = 1;\n";

    file_put_contents($backupFile, $sqlDump);
    $fileSizeKb = round(filesize($backupFile) / 1024, 2);

    echo "✓ Backup successfully created:\n";
    echo "  File: {$backupFile}\n";
    echo "  Size: {$fileSizeKb} KB\n";

    // Auto-rotate: keep only the last 14 backups so disk space never runs out
    $existingBackups = glob($backupDir . '/*.sql');
    if ($existingBackups && count($existingBackups) > 14) {
        usort($existingBackups, fn($a, $b) => filemtime($a) <=> filemtime($b));
        $toDelete = array_slice($existingBackups, 0, count($existingBackups) - 14);
        foreach ($toDelete as $oldFile) {
            @unlink($oldFile);
            echo "  Deleted old backup: " . basename($oldFile) . "\n";
        }
    }

    echo "\nBackup completed successfully!\n";

} catch (\Throwable $e) {
    echo "✕ Backup failed: " . $e->getMessage() . "\n";
    exit(1);
}
