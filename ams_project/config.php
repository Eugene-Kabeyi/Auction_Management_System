<?php
// Database connection class
class DatabaseConfig {
    
    // Store the database connection
    private $conn = null;
    
    // Database settings
    private $config = [
        'host' => 'localhost',      // Server name
        'dbname' => 'AMS',          // Database name
        'user' => 'root',           // Username
        'password' => '',           // Password
        'charset' => 'utf8mb4'      // Character set
    ];

    // Get database connection
    public function getConnection() {
        // Create connection only once (lazy loading)
        if ($this->conn === null) {
            try {
                // Create connection string
                $dsn = "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset={$this->config['charset']}";
                
                // Create PDO connection
                $this->conn = new PDO($dsn, $this->config['user'], $this->config['password']);
                
                // Set PDO options:
                // 1. Throw exceptions on errors
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // 2. Return data as associative arrays
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                // 3. Use real prepared statements (security)
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                
            } catch (PDOException $e) {
                // Connection failed - throw error
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }
        
        // Return the connection
        return $this->conn;
    }
}

// How to use:
try {
    // Create database object
    $db = new DatabaseConfig();
    
    // Get connection
    $conn = $db->getConnection();
    
    // Now you can run queries using $conn
    
} catch (Exception $e) {
    // Show error if connection fails
    echo $e->getMessage();
}
?>