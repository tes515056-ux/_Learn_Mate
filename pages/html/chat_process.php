<?php

session_start();
include '../../dblink.php';

header('Content-Type: application/json');

$message = trim($_POST['message'] ?? '');

if (empty($message)) {

    echo json_encode([
        'error' => 'Message is empty'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| OpenRouter API Key
|--------------------------------------------------------------------------
*/
$env = parse_ini_file(__DIR__ . '/../../.env');
$apiKey = $env['OPENROUTER_API_KEY'];

/*
|--------------------------------------------------------------------------
| AI Request
|--------------------------------------------------------------------------
*/

$data = [

    "model" => "openrouter/auto",

    "messages" => [

        [
            "role" => "system",

            "content" =>

"You are LearnMate AI Tutor.

Rules:

- Explain topics in a simple student-friendly way.
- Use clear paragraphs.
- Use real-life examples whenever possible.
- Use simple bullet points with '-' only.
- Do NOT use Markdown.
- Do NOT use #, ##, ### headings.
- Do NOT use ** or * formatting.
- Do NOT use LaTeX formulas.
- Do NOT use \\[ \\], \\( \\), \\frac, \\Delta.
- Write formulas in plain text.

Example:

Acceleration = Change in Velocity / Time

Use this style:

Topic Name

Simple explanation in paragraph form.

Key Points:
- Point 1
- Point 2
- Point 3

Example:
Provide a real-life example.

Keep answers clean and easy to read."
        ],

        [
            "role" => "user",
            "content" => $message
        ]
    ]
];

/*
|--------------------------------------------------------------------------
| Send Request
|--------------------------------------------------------------------------
*/

$ch = curl_init();

curl_setopt_array($ch, [

    CURLOPT_URL => "https://openrouter.ai/api/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,

    CURLOPT_HTTPHEADER => [

        "Authorization: Bearer " . $apiKey,
        "Content-Type: application/json",
        "HTTP-Referer: http://localhost",
        "X-Title: LearnMate AI Tutor"

    ],

    CURLOPT_POSTFIELDS => json_encode($data)

]);

$response = curl_exec($ch);

if (curl_errno($ch)) {

    echo json_encode([
        'error' => curl_error($ch)
    ]);

    curl_close($ch);
    exit;
}

curl_close($ch);

$result = json_decode($response, true);

if (isset($result['error'])) {

    echo json_encode([
        'error' => $result['error']['message']
    ]);

    exit;
}

$reply = $result['choices'][0]['message']['content'] ?? '';

if (empty($reply)) {

    echo json_encode([
        'error' => 'No response received from AI.'
    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Clean Remaining Markdown / LaTeX
|--------------------------------------------------------------------------
*/

$reply = preg_replace('/#{1,6}\s*/', '', $reply);

$reply = str_replace(
    [
        '**',
        '*',
        '```',
        '\\[',
        '\\]',
        '\\(',
        '\\)',
        '\\frac',
        '\\Delta'
    ],
    [
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        'Delta'
    ],
    $reply
);

$reply = trim($reply);

/*
|--------------------------------------------------------------------------
| Save Chat History
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['user_id'])) {

    $user_id = (int)$_SESSION['user_id'];

    $question = mysqli_real_escape_string(
        $conn,
        $message
    );

    $answer = mysqli_real_escape_string(
        $conn,
        $reply
    );

    mysqli_query(

        $conn,

        "INSERT INTO ai_chat_history
        (
            user_id,
            question,
            answer,
            created_at
        )
        VALUES
        (
            $user_id,
            '$question',
            '$answer',
            NOW()
        )"

    );
}

/*
|--------------------------------------------------------------------------
| Return Response
|--------------------------------------------------------------------------
*/

echo json_encode([
    'reply' => $reply
]);

exit;