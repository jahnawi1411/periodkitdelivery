<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "periodtrackerdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT 
    u.*, 
    k.*, 
    p.*, 
    DATE(p.LastPeriod + INTERVAL 3 DAY) AS delivery 
FROM 
    user u 
LEFT JOIN 
    kits k ON k.U_ID = u.U_ID 
LEFT JOIN 
    perioddetails p ON p.U_ID = u.U_ID;";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

$sql = "SELECT * from deliveryperson";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$delivery = [];
while ($row = $result->fetch_assoc()) {
    $delivery[] = $row;
}

?>

