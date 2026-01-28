<?php
require 'config.php';

class RegisterHandler extends DatabaseConfig
{
    protected $conn;

    public function __construct()
    {
        $this->conn = $this->getConnection();
    }

    public function handleRegistration()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // Collect form data
            $national_id = $_POST['national_id'];
            $business_id = !empty($_POST['business_id']) ? $_POST['business_id'] : NULL;
            $role_id = $_POST['role_id'];
            $firstname = $_POST['firstname'];
            $secondname = !empty($_POST['secondname']) ? $_POST['secondname'] : NULL;
            $surname = $_POST['surname'];
            $business_name = !empty($_POST['business_name']) ? $_POST['business_name'] : NULL;
            $phone = $_POST['phone_number'];
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            // SQL with named placeholders
            try {
                $sql = "
                INSERT INTO users
                (national_id, business_id, role_id, firstname, secondname,
                 surname, business_name, phone_number, email, username, password_hash)
                VALUES
                (:national_id, :business_id, :role_id, :firstname, :secondname,
                 :surname, :business_name, :phone, :email, :username, :password_hash)
                ";

                $stmt = $this->conn->prepare($sql);

                $stmt->execute([
                    ':national_id' => $national_id,
                    ':business_id' => $business_id,
                    ':role_id' => $role_id,
                    ':firstname' => $firstname,
                    ':secondname' => $secondname,
                    ':surname' => $surname,
                    ':business_name' => $business_name,
                    ':phone' => $phone,
                    ':email' => $email,
                    ':username' => $username,
                    ':password_hash' => $password // later: hash with password_hash()
                ]);

                // Redirect without echo before header
                header("Location: login.html");
                exit();

            } catch (PDOException $e) {
                echo "❌ Registration failed: " . $e->getMessage();
            }
        }
    }
}

// Create object and call method separately
$registerHandler = new RegisterHandler();
$registerHandler->handleRegistration();
?>
