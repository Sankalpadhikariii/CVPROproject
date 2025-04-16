<?php
header('Content-Type: application/json');

// Retrieve the prompt from the POST request
$prompt = isset($_POST['prompt']) ? $_POST['prompt'] : '';

if (empty($prompt)) {
    echo json_encode(['success' => false, 'error' => 'No prompt provided']);
    exit;
}

// OpenAI API configuration
$api_key = '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'; // Replace with your actual OpenAI API key
$api_url = 'https://api.openai.com/v1/chat/completions';

// Prepare the request data
$data = [
    'model' => 'gpt-3.5-turbo', // or 'gpt-4' if you have access
    'messages' => [
        ['role' => 'user', 'content' => $prompt]
    ],
    'max_tokens' => 500,
    'temperature' => 0.7
];

// Initialize cURL
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
]);

// Execute the request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Process the response
if ($http_code === 200) {
    $response_data = json_decode($response, true);
    $text = $response_data['choices'][0]['message']['content'] ?? '';
    echo json_encode(['success' => true, 'text' => trim($text)]);
} else {
    echo json_encode(['success' => false, 'error' => 'API request failed', 'details' => $response]);
}
?>