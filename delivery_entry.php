<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "periodtrackerdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$did = $_POST['did'];
$uid = $_POST['uid'];
$kid = $_POST['kid'];
$dpid = $_POST['dpid'];

$checkUser = $conn->prepare("SELECT id FROM users WHERE id = ?");
$checkUser->bind_param("s", $uid);
$checkUser->execute();
$checkUser->store_result();
if ($checkUser->num_rows == 0) {
    echo "Error: User ID (UID) does not exist!";
    exit();
}

$checkKit = $conn->prepare("SELECT id FROM kits WHERE id = ?");
$checkKit->bind_param("s", $kid);
$checkKit->execute();
$checkKit->store_result();
if ($checkKit->num_rows == 0) {
    echo "Error: Kit ID (KID) does not exist!";
    exit();
}

$checkDeliveryPerson = $conn->prepare("SELECT id FROM deliveryperson WHERE id = ?");
$checkDeliveryPerson->bind_param("s", $dpid);
$checkDeliveryPerson->execute();
$checkDeliveryPerson->store_result();
if ($checkDeliveryPerson->num_rows == 0) {
    echo "Error: Delivery Person ID (DPID) does not exist!";
    exit();
}

$stmt = $conn->prepare("INSERT INTO deliveries (did, uid, kid, dpid) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $did, $uid, $kid, $dpid);

if ($stmt->execute()) {
    echo "✅ Successfully Submitted!";
} else {
    echo "⚠️ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
