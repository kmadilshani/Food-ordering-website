<?php
// install.php - One-time database creation + schema execution + optional seed
// Usage examples:
//   http://localhost/Food_ordering_web-main/backend/php/install.php?db=speedy_eats
//   http://localhost/Food_ordering_web-main/backend/php/install.php?db=speedy_eats&seed=1
// IMPORTANT: Remove or protect this file after successful install.

ini_set('display_errors', 1);
error_reporting(E_ALL);

$requestedDb = $_GET['db'] ?? 'speedy_eats';
$doSeed = isset($_GET['seed']);

$host = 'localhost';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function line($m){ echo h($m) . "<br>\n"; }

try {
    // 1. Connect without DB and create database if missing
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$requestedDb}` CHARACTER SET $charset COLLATE {$charset}_general_ci");
    line("Database ensured: {$requestedDb}");

    // 2. Reconnect selecting database
    $pdo = new PDO("mysql:host=$host;dbname={$requestedDb};charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
    ]);
    line('Connected to database.');

    // 3. Load schema.sql
    $schemaPath = __DIR__ . '/schema.sql';
    if(!file_exists($schemaPath)) throw new Exception('schema.sql not found');
    $sql = file_get_contents($schemaPath);

    // Basic split on ; (naive) – better parser skipped for simplicity
    // We'll just run the whole file allowing multi-statements
    $pdo->exec($sql);
    line('Schema executed.');

    // 4. Verify core tables
    $tables = ['users','categories','foods','orders','order_items'];
    foreach($tables as $t){
        $res = $pdo->query("SHOW TABLES LIKE '$t'")->fetch();
        line($res ? "Table ok: $t" : "Missing table: $t");
    }

    // 5. Optional seed
    if($doSeed){
        line('Running seed...');
        require __DIR__ . '/seed.php';
    } else {
        line('Skip seeding (append &seed=1 to URL to seed).');
    }

    line('Install complete. Please DELETE backend/php/install.php for security.');
} catch (Exception $e) {
    line('Error: ' . $e->getMessage());
    http_response_code(500);
}
