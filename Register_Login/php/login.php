<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    die("Error: Email and password are required.");
}

// Database connection
$conn = new mysqli('localhost', 'root', 'Swetha@m8', 'guvi_db');
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM registration WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['email'] = $email;
    $_SESSION['user_id'] = $user['id'];
    error_log("Login successful for email: $email");
    header("Location: ../profile.html");
    exit();
} else {
    die("Error: Invalid email or password. Email used: '$email'");
}

$stmt->close();
$conn->close();
ob_end_flush();
?>