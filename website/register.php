
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show errors in output
ini_set('log_errors', 1);
ini_set('error_log', DIR . '/php-error.log'); // save errors to a log file
header('Content-Type: application/json');

// Replace with your actual DB credentials
$host = "localhost";
$user = "root";
$password = "";
$dbname = "db_name"; // ✅ change this

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

// Get POST data
$fullName = $_POST['fullName'] ?? '';
$email = $_POST['emailRegister'] ?? '';
$passwordRaw = $_POST['passwordRegister'] ?? '';

if ($fullName === '' || $email === '' || $passwordRaw === '') {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

// Hash password for security
$password = password_hash($passwordRaw, PASSWORD_DEFAULT);

// Insert into DB
$stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fullName, $email, $password);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "User already exists or insert failed"]);
}

$stmt->close();
$conn->close();