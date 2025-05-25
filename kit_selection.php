<?php
session_start();
ob_start(); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "periodtrackerdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("<h2>❌ Connection failed: " . $conn->connect_error . "</h2>");
}

$kit_type = $_GET['kit'];

$check = $conn->prepare("SELECT * FROM inventory_items WHERE category = ? AND stock>0");
$check->bind_param("s", $kit_type);
$check->execute();
$inventoryResult = $check->get_result();

if ($inventoryResult->num_rows === 0) {
    echo "
    <div class='error-box'>
        <h2>🚫 Out of Stock</h2>
        <p>Sorry, the <strong>$kit_type</strong> kit is currently unavailable.</p>
        <a href='kit.html'>← Go Back</a>
    </div>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .error-box {
            background-color: #ffe0e0;
            padding: 30px;
            border: 1px solid #ff9999;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .error-box h2 {
            color: #cc0000;
        }
        .error-box p {
            font-size: 16px;
            color: #333;
        }
        .error-box a {
            display: inline-block;
            margin-top: 12px;
            text-decoration: none;
            color: #fff;
            background: #cc0000;
            padding: 10px 18px;
            border-radius: 6px;
        }
        .error-box a:hover {
            background: #a80000;
        }
    </style>";
    exit;
}
$stmt = $conn->prepare("INSERT INTO kits (TYPE, U_ID) VALUES (?, ?)");
$stmt->bind_param("si", $kit_type, $_SESSION['U_ID']);

if ($stmt->execute()) {
    header("Location: available_products.html");
    exit;
} else {
    echo "<h2>⚠️ Error submitting your request. Try again later.</h2>";
}


$stmt->close();
$conn->close();
ob_end_flush(); 
?>
