<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.action.php");
    exit;
}

// Database connection
try {
    $conn = new PDO('mysql:host=localhost;dbname=cv_builder', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Initialize variables
$errorMessages = [];
$resumeData = null;

// Fetch existing resume data if needed
if (isset($_GET['resume_id'])) {
    $resumeId = $_GET['resume_id'];

    try {
        // Prepare and execute the query to fetch existing resume
        $stmt = $conn->prepare("SELECT * FROM resumes WHERE id = :resume_id");
        $stmt->bindParam(':resume_id', $resumeId, PDO::PARAM_INT);
        $stmt->execute();

        $resumeData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If resume not found
        if (!$resumeData) {
            $errorMessages[] = "Resume not found.";
        }
    } catch (PDOException $e) {
        $errorMessages[] = "Error fetching resume data: " . $e->getMessage();
    }
}

// Handle form submission for editing resume
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input data
    $fullName = trim($_POST['fullName']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if (empty($fullName)) {
        $errorMessages[] = "Full Name is required.";
    }

    if (!$email) {
        $errorMessages[] = "A valid Email is required.";
    }

    // If no errors, proceed with updating the resume
    if (empty($errorMessages)) {
        try {
            // Update resume in the database
            $stmt = $conn->prepare("UPDATE resumes SET full_name = :full_name, email = :email WHERE id = :resume_id");
            $stmt->execute([
                'full_name' => $fullName,
                'email' => $email,
                'resume_id' => $resumeId
            ]);

            // Redirect on success
            header("Location: dashboard.php"); // or wherever you want to redirect after success
            exit;

        } catch (PDOException $e) {
            $errorMessages[] = "Error updating resume: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resume</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Resume</h1>

        <!-- Show success or error message -->
        <?php if (!empty($errorMessages)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errorMessages as $message): ?>
                        <li><?= $message ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <h5>Personal Information</h5>
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" name="fullName" class="form-control" value="<?= htmlspecialchars($resumeData['full_name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($resumeData['email'] ?? '') ?>" required>
            </div>

            <!-- Add other fields (experience, education, skills) as needed -->

            <button type="submit" class="btn btn-success mt-3">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
