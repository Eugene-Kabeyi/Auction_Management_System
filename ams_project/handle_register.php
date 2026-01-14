<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect form data
    $national_id   = $_POST['national_id'];
    $business_id   = !empty($_POST['business_id']) ? $_POST['business_id'] : NULL;
    $role_id       = $_POST['role_id'];
    $firstname     = $_POST['firstname'];
    $secondname    = !empty($_POST['secondname']) ? $_POST['secondname'] : NULL;
    $surname       = $_POST['surname'];
    $business_name = !empty($_POST['business_name']) ? $_POST['business_name'] : NULL;
    $phone         = $_POST['phone_number'];
    $email         = $_POST['email'];
    $username      = $_POST['username'];
    $password      = $_POST['password'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL with named placeholders
    $sql = "
        INSERT INTO users
        (national_id, business_id, role_id, firstname, secondname,
         surname, business_name, phone_number, email, username, password_hash)
        VALUES
        (:national_id, :business_id, :role_id, :firstname, :secondname,
         :surname, :business_name, :phone, :email, :username, :password_hash)
    ";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Execute with data
    $stmt->execute([
        ':national_id'   => $national_id,
        ':business_id'   => $business_id,
        ':role_id'       => $role_id,
        ':firstname'     => $firstname,
        ':secondname'    => $secondname,
        ':surname'       => $surname,
        ':business_name' => $business_name,
        ':phone'         => $phone,
        ':email'         => $email,
        ':username'      => $username,
        ':password_hash' => $hashed_password
    ]);

    echo "✅ Registration successful! <a href='login.php'>Login here</a>";
}
?>
