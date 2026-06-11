<?php

session_start();

$topic = $_GET['topic'] ?? '';

if(empty($topic)){
    die("Topic not found.");
}

$env = parse_ini_file(__DIR__ . '/../../.env');
$apiKey = $env['OPENROUTER_API_KEY'];

$prompt = "
Create 5 multiple choice quiz questions about:

$topic

Return ONLY valid JSON.

Format:

[
  {
    \"question\":\"Question here\",
    \"a\":\"Option A\",
    \"b\":\"Option B\",
    \"c\":\"Option C\",
    \"d\":\"Option D\",
    \"answer\":\"A\"
  }
]
";

$data = [
    "model" => "openrouter/auto",
    "messages" => [
        [
            "role" => "system",
            "content" => "You are a quiz generator. Return JSON only."
        ],
        [
            "role" => "user",
            "content" => $prompt
        ]
    ]
];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://openrouter.ai/api/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $apiKey,
        "Content-Type: application/json",
        "HTTP-Referer: http://localhost",
        "X-Title: LearnMate Quiz Generator"
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);

if(curl_errno($ch)){
    die(curl_error($ch));
}

curl_close($ch);

$result = json_decode($response, true);

$content =
    $result['choices'][0]['message']['content']
    ?? '';

if(empty($content)){
    die("No quiz generated.");
}

$content = trim($content);

$content = preg_replace('/```json/', '', $content);
$content = preg_replace('/```/', '', $content);

$questions = json_decode($content, true);

if(!$questions){
    die("Failed to parse quiz JSON.");
}

$_SESSION['quiz_topic'] = $topic;
$_SESSION['quiz_questions'] = $questions;

header("Location: quiz.php");
exit;