<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "periodtrackerdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    fetchDeliveries($conn);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateStatus($conn);
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

function fetchDeliveries($conn) {
    $sql = "SELECT o.ORDER_ID, k.TYPE, u.Address, o.STATUS FROM order_details o LEFT JOIN kits k on k.K_ID=o.K_ID LEFT JOIN user u on k.U_ID=u.U_ID;";
    $result = $conn->query($sql);

    $deliveries = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $deliveries[] = [
                "delivery_id" => $row["ORDER_ID"],
                "kit_items" => $row["TYPE"],
                "address" => $row["Address"],
                "status" => $row["STATUS"]
            ];
        }
    }

    header('Content-Type: application/json');
    
    echo json_encode($deliveries);
}

function updateStatus($conn) {
    $delivery_id = isset($_POST['delivery_id']) ? $_POST['delivery_id'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;

    if (!$delivery_id || !$status) {
        echo "Error: Missing delivery ID or status";
        exit;
    }

    $delivery_id = $conn->real_escape_string($delivery_id);
    $status = $conn->real_escape_string($status);

    $sql = "UPDATE order_details SET STATUS = '$status' WHERE ORDER_ID = '$delivery_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . $conn->error;
    }
}

$conn->close();
?>