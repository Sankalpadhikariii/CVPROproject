<?php
require_once 'actions/database.class.php';

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents('php://input'), true);
$resumeId = $data['id'];

$stmt = $conn->prepare("DELETE FROM resumes WHERE id = ?");
$stmt->bind_param('i', $resumeId);
if ($stmt->execute()) {
    echo "Resume deleted successfully.";
} else {
    echo "Error deleting resume: " . $conn->error;
}
?>
