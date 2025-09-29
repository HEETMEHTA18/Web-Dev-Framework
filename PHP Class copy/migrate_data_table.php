<?php
/**
 * Migration helper: ensure `data` table has `email` and `mobile` columns.
 * Run this once (open in a browser: http://localhost/PHP%20Class/migrate_data_table.php)
 */
try {
    $dsn = 'mysql:host=localhost;dbname=test;charset=utf8mb4';
    $username = 'root';
    $password = '';
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Check if table exists; create if missing
    $res = $pdo->query("SHOW TABLES LIKE 'data'")->fetch();
    if (!$res) {
        echo "Table `data` does not exist in database `test`. Creating it...<br>\n";
        $createSql = "CREATE TABLE `data` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `mobile` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $pdo->exec($createSql);
        echo "Created table `data`.\n";
    }

    // Get existing columns
    $cols = [];
    $stmt = $pdo->query("SHOW COLUMNS FROM `data`");
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) {
        $cols[] = $r['Field'];
    }

    $queries = [];
    if (!in_array('email', $cols)) {
        $queries[] = "ALTER TABLE `data` ADD COLUMN `email` VARCHAR(255) NULL AFTER `name`";
    }
    if (!in_array('mobile', $cols)) {
        $queries[] = "ALTER TABLE `data` ADD COLUMN `mobile` VARCHAR(50) NULL AFTER `email`";
    }

    if (empty($queries)) {
        echo "No changes needed. `data` table already has `email` and `mobile` columns.\n";
    } else {
        foreach ($queries as $q) {
            echo "Running: $q<br>\n";
            $pdo->exec($q);
        }
        echo "Migration complete. Columns added.\n";
    }

    echo "\nCurrent columns:\n";
    foreach ($cols as $c) echo "- " . htmlspecialchars($c) . "<br>\n";

} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}

?>
