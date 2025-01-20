<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=cv_builder', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$resume = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View CV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>View Your CV</h1>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        <div class="card">
            <div class="card-body">
                <?php if ($resume): ?>
                    <h5>Full Name: <?php echo htmlspecialchars($resume['full_name']); ?></h5>
                    <p>Email: <?php echo htmlspecialchars($resume['email']); ?></p>
                    <p><strong>Experience:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($resume['experience'])); ?></p>
                    <p><strong>Education:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($resume['education'])); ?></p>
                    <p><strong>Skills:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($resume['skills'])); ?></p>
                <?php else: ?>
                    <p>No CV found. Create one <a href="cvbuilder.php">here</a>.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
