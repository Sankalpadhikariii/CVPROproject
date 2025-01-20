<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $profileSummary = $_POST['profileSummary'];
    $experience = implode(", ", $_POST['experience']);
    $education = implode(", ", $_POST['education']);
    $skills = implode(", ", $_POST['skills']);

    // You can save this data to a file, database, or email it
    $cvContent = "
    Name: $fullName
    Email: $email

    Profile Summary:
    $profileSummary

    Experience:
    $experience

    Education:
    $education

    Skills:
    $skills
    ";

    // Example: Save to a text file
    file_put_contents("cv_output.txt", $cvContent);

    // Redirect or display a success message
    echo "CV submitted successfully!";
}
?>
