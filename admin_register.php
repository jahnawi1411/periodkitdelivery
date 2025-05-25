<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "periodtrackerdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$admin_name = $_POST['adminName'];
$admin_email = $_POST['adminEmail'];
$admin_password = $_POST['adminPassword'];

$checkEmail = $conn->prepare("SELECT id FROM admins WHERE email = ?");
$checkEmail->bind_param("s", $admin_email);
$checkEmail->execute();
$checkEmail->store_result();

if ($checkEmail->num_rows > 0) {
    echo "Email is already registered!";
    exit();
}

$hashed_password = password_hash($admin_password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $admin_name, $admin_email, $hashed_password);

if ($stmt->execute()) {
    echo "Registration successful! Redirecting to login...";
} else {
    echo " Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
