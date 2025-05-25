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

$user_id = $_SESSION['U_ID'];
$last_period_date = $_POST['last_period_date'];
$avg_cycle = $_POST['avg_cycle'];
$feedback = $_POST['feedback'];
echo  "this is last". $last_period_date;

$stmt = $conn->prepare("INSERT INTO perioddetails (U_ID, LastPeriod, CycleLength, Feedback) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $user_id, $last_period_date, $avg_cycle, $feedback);

if ($stmt->execute()) {
    echo "✅ Successfully Updated!";
} else {
    echo "⚠️ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
