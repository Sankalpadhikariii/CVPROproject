<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | CV Maker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" href="logo.png">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .dashboard-header {
            background: linear-gradient(to right, #007bff, #6610f2);
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .dashboard-content {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .btn-container a {
            margin-right: 10px;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <p>Your email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        </div>

        <div class="dashboard-content">
            <h2 class="mb-4">Dashboard Options</h2>
            <p>Access your tools to manage your profile, create or edit your CV, and much more.</p>

            <div class="btn-container">
                <a href="profile.php" class="btn btn-primary">Edit Profile</a>
                <a href="cvbuilder.php" class="btn btn-success">Create or Edit CV</a>
                <a href="viewcv.php" class="btn btn-info">View CV</a>
                <a href="settings.php" class="btn btn-secondary">Account Settings</a>
                <a href="javascript:void(0);" class="btn btn-danger" id="logoutBtn">Logout</a>
            </div>
        </div>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> CV Maker. All Rights Reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script>
        // Logout Button Functionality
        document.getElementById('logoutBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) {
                // Redirect to logout action
                window.location.href = 'logout.action.php';
            }
        });
    </script>
</body>

</html>
