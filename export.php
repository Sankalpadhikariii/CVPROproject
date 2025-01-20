<?php
require 'fpdf/fpdf.php';
$pdo = new PDO('mysql:host=localhost;dbname=cv_builder', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $resumeId = $_GET['id'];

    // Fetch resume from database
    $stmt = $pdo->prepare("SELECT * FROM resumes WHERE id = :id");
    $stmt->execute(['id' => $resumeId]);
    $resume = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resume) {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Resume', 0, 1, 'C');
        $pdf->Ln(10);

        // Personal Information
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Name: " . $resume['full_name'], 0, 1);
        $pdf->Cell(0, 10, "Email: " . $resume['email'], 0, 1);
        $pdf->Ln(10);

        // Experience
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Experience:", 0, 1);
        $experience = json_decode($resume['experience'], true);
        foreach ($experience as $exp) {
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, "Job: " . $exp[0] . " | Company: " . $exp[1], 0, 1);
        }
        $pdf->Ln(10);

        // Education
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Education:", 0, 1);
        $education = json_decode($resume['education'], true);
        foreach ($education as $edu) {
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, "Degree: " . $edu[0] . " | Institution: " . $edu[1], 0, 1);
        }
        $pdf->Ln(10);

        // Skills
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "Skills:", 0, 1);
        $skills = json_decode($resume['skills'], true);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, implode(", ", $skills), 0, 1);

        $pdf->Output();
        exit;
    }
}
header("Location: dashboard.php");
?>