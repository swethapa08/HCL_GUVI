<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : ''; // In production, hash this!
    $number = isset($_POST['number']) ? trim($_POST['number']) : '';
    
    // Assume user ID from session or POST (critical for targeted updates - add a hidden input in profile.html if needed)
    $userId = isset($_POST['userId']) ? (int)$_POST['userId'] : 1; // Example: default to ID 1; replace with real logic (e.g., $_SESSION['user_id'])

    // Basic validation
    $errors = [];
    if (empty($firstName)) $errors[] = "First Name is required";
    if (empty($lastName)) $errors[] = "Last Name is required";
    if (empty($gender)) $errors[] = "Gender is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid Email is required";
    if (empty($password)) $errors[] = "Password is required";
    if (empty($number)) $errors[] = "Phone Number is required";

    if (empty($errors)) {
        // Database connection (replace with your details)
        $servername = "localhost";
        $db_username = "root"; // e.g., 'root'
        $db_password = "Swetha@m8"; // e.g., ''
        $dbname = "guvi_db"; // e.g., 'your_db'

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute UPDATE query (use prepared statements to prevent SQL injection)
        $sql = "UPDATE registration SET firstName = ?, lastName = ?, gender = ?, email = ?, password = ?, number = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ssssssi", $firstName, $lastName, $gender, $email, $password, $number, $userId);
        
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($conn->affected_rows > 0) {
                echo "Update successful! Redirecting..."; // For testing; remove in production
                // Redirect to index.html on success
                header("Location: ../index.html");
                exit();
            } else {
                echo "No rows updated. Check if user ID $userId exists.";
            }
        } else {
            echo "Execute failed: " . $stmt->error;
        }
        
        $stmt->close();
        $conn->close();
    } else {
        // Display errors (for testing; in production, redirect back with errors)
        echo "<h2>Errors:</h2><ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
} else {
    echo "Invalid request method.";
}
?>