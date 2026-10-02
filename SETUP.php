<?php
// Simple setup script for the Food Ordering Web Project
echo "Setting up Speedy Eats Food Ordering System...\n";

// Set up database
$db = 'speedy_eats';
$host = 'localhost';
$charset = 'utf8mb4';

// Try common XAMPP configurations
$configs = [
    ['user' => 'root', 'pass' => ''],
    ['user' => 'root', 'pass' => 'root'],
    ['user' => 'root', 'pass' => 'password'],
    ['user' => 'root', 'pass' => 'admin'],
];

$pdo = null;
$workingConfig = null;
foreach ($configs as $config) {
    try {
        echo "Trying MySQL connection with user: {$config['user']}, pass: " . (empty($config['pass']) ? '(empty)' : '***') . "\n";
        $pdo = new PDO("mysql:host=$host;charset=$charset", $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $workingConfig = $config;
        echo "✓ Connected to MySQL successfully!\n";
        break;
    } catch (PDOException $e) {
        echo "✗ Failed: " . $e->getMessage() . "\n";
        continue;
    }
}

if (!$pdo || !$workingConfig) {
    throw new Exception("Could not connect to MySQL. Please check if XAMPP MySQL service is running and credentials are correct.");
}

try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET $charset COLLATE {$charset}_general_ci");
    echo "✓ Database '{$db}' created/verified\n";

    // Reconnect selecting database
    $pdo = new PDO("mysql:host=$host;dbname={$db};charset=$charset", $workingConfig['user'], $workingConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
    ]);
    echo "✓ Connected to database\n";

    // Load schema
    $schemaPath = __DIR__ . '/backend/php/schema.sql';
    if(!file_exists($schemaPath)) {
        throw new Exception('schema.sql not found at: ' . $schemaPath);
    }
    $sql = file_get_contents($schemaPath);
    $pdo->exec($sql);
    echo "✓ Database schema executed\n";

    // Verify tables
    $tables = ['users','categories','foods','orders','order_items'];
    foreach($tables as $t){
        $res = $pdo->query("SHOW TABLES LIKE '$t'")->fetch();
        echo ($res ? "✓" : "✗") . " Table: $t\n";
    }

    // Seed database
    echo "✓ Running seed data...\n";
    require __DIR__ . '/backend/php/seed.php';

    echo "\n🎉 Setup complete! Your food ordering system is ready.\n";
    echo "📝 Default credentials:\n";
    echo "   Admin: admin@example.com / secret123\n";
    echo "   Demo:  demo@example.com / secret123\n";
    echo "\n🌐 Access your site at: http://localhost/Food_ordering_web-main/\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
