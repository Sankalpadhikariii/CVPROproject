<?php
require_once 'actions/database.class.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resumeId = $_POST['id'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    // Handle experience, education, skills as JSON
    $experience = json_encode($_POST['experience']);
    $education = json_encode($_POST['education']);
    $skills = json_encode($_POST['skills']);

    $stmt = $conn->prepare("UPDATE resumes SET full_name = ?, email = ?, experience = ?, education = ?, skills = ? WHERE id = ?");
    $stmt->bind_param('sssssi', $fullName, $email, $experience, $education, $skills, $resumeId);

    if ($stmt->execute()) {
        echo "Resume updated successfully!";
    } else {
        echo "Error updating resume: " . $conn->error;
    }
}
?>
