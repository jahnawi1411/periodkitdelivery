<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "periodtrackerdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $hashedPassword = md5($password);

    $stmt = $conn->prepare("SELECT * FROM deliveryperson WHERE Email = ? AND Password = ?");
    $stmt->bind_param("ss", $email, $hashedPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["user"] = $email;
        $_SESSION["U_ID"] = $row['DP_ID'];
        echo "Login successful!"; 
    } else {
        echo "Invalid email or password!";
    }

    $stmt->close();
}

$conn->close();
?>
