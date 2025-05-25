<?php
$servername = "localhost";
$username = "root"; 
$password = "";      
$dbname = "periodtrackerdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $dob = $_POST["dob"];
    $email = trim($_POST["email"]);
    $contact = trim($_POST["contact"]);
    $password = md5($_POST["password"]);

    $check_email = "SELECT * FROM deliveryperson WHERE Email='$email' OR Phone='$contact'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        echo "User already registered with this email, contact, or Aadhar!";
    } else {
        $sql = "INSERT INTO deliveryperson (Name, DoB, Email, Phone, Password) 
                VALUES ('$name', '$dob', '$email', '$contact', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
