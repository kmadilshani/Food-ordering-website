<?php
// Database connection using PDO
class Database {
    private $host = 'localhost';
    private $db_name = 'speedy_eats';
    private $username = 'root'; // default XAMPP user
    private $password = 'root'; // common XAMPP password - adjust if needed
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        // Try different common XAMPP password configurations
        $passwords = ['', 'root', 'password', 'admin'];
        
        foreach ($passwords as $password) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                $this->conn = new PDO($dsn, $this->username, $password, $options);
                return $this->conn; // Success!
            } catch (PDOException $e) {
                // Try next password
                continue;
            }
        }
        
        // If all passwords failed
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed', 'details' => 'Could not connect with any common XAMPP configuration. Please check your MySQL service and credentials.']);
        exit;
    }
}
