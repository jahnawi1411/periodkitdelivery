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

$uid = $_SESSION['U_ID'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'] ?? '';
    $oldPass = $_POST['oldPassword'] ?? '';
    $newPass = $_POST['newPassword'] ?? '';

    $stmt = $conn->prepare("SELECT Password FROM user WHERE U_ID = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "User not found.";
        exit;
    }

    $currentHashedPassword = $user['Password'];

    if (md5($oldPass) !== $currentHashedPassword) {
        echo "Old password is incorrect.";
        exit;
    }

    $stmt = $conn->prepare("UPDATE user SET address = ? WHERE U_ID = ?");
    $stmt->bind_param("si", $address, $uid);
    $stmt->execute();

    if (!empty($newPass)) {
        if ($oldPass === $newPass) {
            echo "New password cannot be the same as old password.";
            exit;
        }

        $hashedNewPass = md5($newPass);
        $stmt = $conn->prepare("UPDATE user SET Password = ? WHERE U_ID = ?");
        $stmt->bind_param("si", $hashedNewPass, $uid);
        $stmt->execute();
    }

    echo "Profile updated successfully.";
    exit;
} else {
    $stmt = $conn->prepare("SELECT * FROM user WHERE U_ID = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>
