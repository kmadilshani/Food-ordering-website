<?php
// Test script to verify API endpoints
echo "<h1>Speedy Eats API Test</h1>";

$baseUrl = "http://localhost/Food_ordering_web-main/backend/php/api/";

$endpoints = [
    "categories_list.php" => "Categories List",
    "foods_list.php" => "Foods List", 
    "foods_search.php?q=pizza" => "Food Search (Pizza)",
];

echo "<h2>Testing API Endpoints:</h2>";

foreach ($endpoints as $endpoint => $description) {
    echo "<h3>$description</h3>";
    echo "<p>Testing: <code>$endpoint</code></p>";
    
    $url = $baseUrl . $endpoint;
    echo "<p>URL: <a href='$url' target='_blank'>$url</a></p>";
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'method' => 'GET',
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "<p style='color: red;'>❌ Failed to connect</p>";
    } else {
        $data = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "<p style='color: green;'>✅ Success</p>";
            echo "<pre>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "</pre>";
        } else {
            echo "<p style='color: orange;'>⚠️ Response received but not valid JSON</p>";
            echo "<pre>" . htmlspecialchars($response) . "</pre>";
        }
    }
    echo "<hr>";
}

echo "<h2>Database Connection Test</h2>";
try {
    require_once 'backend/php/config/database.php';
    $db = (new Database())->getConnection();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test a simple query
    $stmt = $db->query("SELECT COUNT(*) as count FROM foods");
    $result = $stmt->fetch();
    echo "<p>Foods in database: " . $result['count'] . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please ensure MySQL is running and database is set up.</p>";
}

echo "<h2>Next Steps</h2>";
echo "<ul>";
echo "<li>If all tests pass, your system is ready!</li>";
echo "<li>Visit <a href='index.html'>index.html</a> to see the main site</li>";
echo "<li>Visit <a href='admin/index.html'>admin/index.html</a> for admin panel</li>";
echo "<li>Use credentials: admin@example.com / secret123</li>";
echo "</ul>";
?>

