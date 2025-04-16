<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use Dotenv\Dotenv;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$api_key = $_ENV['OPENAI_API_KEY'];

$recommended_skills = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_description = $_POST['job_description'];

    // Use GPT-3.5 to recommend skills
    $client = new Client();

    try {
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => "Bearer {$api_key}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that suggests skills for a CV based on job descriptions. Respond with a comma-separated list of relevant skills only.'],
                    ['role' => 'user', 'content' => "Suggest skills based on this job description: $job_description"],
                ],
                'temperature' => 0.7,
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        $recommended_skills = $body['choices'][0]['message']['content'];

    } catch (Exception $e) {
        $recommended_skills = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommend Skills</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        textarea {
            resize: none;
        }
        .btn-primary {
            width: 100%;
        }
        .recommended-skills {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recommend Skills</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="job_description" class="form-label">Job Description</label>
                <textarea id="job_description" name="job_description" class="form-control" rows="6" placeholder="Paste the job description here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Get Recommendations</button>
        </form>

        <?php if (!empty($recommended_skills)): ?>
            <div class="recommended-skills">
                <h2>Recommended Skills</h2>
                <p><?= nl2br(htmlspecialchars($recommended_skills)) ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
