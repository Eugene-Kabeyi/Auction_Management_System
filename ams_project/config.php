<?php
class DatabaseConfig {
    protected $conn;
    public $host = "localhost";
    public $db = "AMS";
    public $user = "root";
    public $password = "";

// Establish database connection in a protected method
protected function connect(){
    $host = $this->host;
    $db = $this->db;
    $user = $this->user;
    $password = $this->password;

    $conn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    try{
        $conn= new PDO ($conn, $user, $password);

        // Throw exceptions on errors
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch results as associative arrays
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Use real prepared statements
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        echo "✅ Database connection successful.";
        return $conn;
    }catch (PDOException $e){
        die("❌ Database connection failed: " . $e->getMessage());
    }
}
// Get the database connection and  make it accessible
public function getConnection(){
    $this->connect();
    return $this->conn;
}
}
// Create a database connection
$conn = (new DatabaseConfig())->getConnection();


?>