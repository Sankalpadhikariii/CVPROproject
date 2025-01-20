<?php
session_start();
require_once('database.class.php');
require_once('function.class.php');

// Check if the user is logged in and the session contains a valid user ID
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html"); // Redirect to login if not authenticated
    exit;
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Validate input before processing
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash the new password

        try {
            $db = new Database();
            $conn = $db->getConnection();

            // Use parameterized query to prevent SQL injection
            $query = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            // Execute the query with the parameters
            if ($stmt->execute([$new_password, $user_id])) {
                header("Location: ../assets/success.php"); // Redirect to success page
                exit;
            } else {
                echo "Password change failed. Please try again.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    } else {
        echo "New password is required.";
    }
} else {
    echo "Invalid request method.";
}
?>
