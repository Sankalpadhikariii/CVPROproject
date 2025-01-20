<?php
// Enable error reporting for development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Database connection
    $pdo = new PDO('mysql:host=localhost;dbname=cv_builder', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form data is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        // Sanitize and validate input
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required.';
            header('Location: /CVPRO/login.php');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email format.';
            header('Location: /CVPRO/login.php');
            exit();
        }

        // Check if the user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE Email_address = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id();

                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['Email_address'];

                // Redirect to dashboard or another page
                header('Location: /CVPRO/dashboard.php');
                exit();
            } else {
                $_SESSION['error'] = 'Incorrect password.';
            }
        } else {
            $_SESSION['error'] = 'User does not exist.';
        }
    } else {
        $_SESSION['error'] = 'Invalid form submission.';
    }
} catch (PDOException $e) {
    // Log error (for development purposes, display it)
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    // Handle general errors
    $_SESSION['error'] = 'An unexpected error occurred: ' . $e->getMessage();
}

// Redirect back to login page on failure
header('Location: /CVPRO/login.php');
exit();
?>
