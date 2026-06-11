<?php

session_start();

include '../../dblink.php';

if(
    !isset($_SESSION['quiz_questions']) ||
    empty($_SESSION['quiz_questions'])
){
    die("No quiz found.");
}

$questions = $_SESSION['quiz_questions'];

$totalQuestions = count($questions);

$correctAnswers = 0;

$review = [];

foreach($questions as $index => $question){

    $userAnswer = $_POST["q".$index] ?? '';

    $correctAnswer =
        strtoupper(
            trim($question['answer'])
        );

    if(
        strtoupper($userAnswer)
        ===
        $correctAnswer
    ){
        $correctAnswers++;
    }
    else{

        $review[] = [

            'question' =>
                $question['question'],

            'your_answer' =>
                $userAnswer,

            'correct_answer' =>
                $correctAnswer

        ];

    }

}

$score =
    round(
        ($correctAnswers / $totalQuestions)
        * 100
    );

/*
|--------------------------------------------------------------------------
| Save Result Session
|--------------------------------------------------------------------------
*/

$_SESSION['quiz_score'] = $score;
$_SESSION['quiz_correct'] = $correctAnswers;
$_SESSION['quiz_total'] = $totalQuestions;
$_SESSION['quiz_review'] = $review;

/*
|--------------------------------------------------------------------------
| Save Result Database
|--------------------------------------------------------------------------
*/

$user_id =
    $_SESSION['user_id']
    ?? 1;

/*
|--------------------------------------------------------------------------
| Quiz Topic
|--------------------------------------------------------------------------
*/

$topic =
    $_SESSION['quiz_topic']
    ?? 'General Quiz';

/*
|--------------------------------------------------------------------------
| Detect Subject
|--------------------------------------------------------------------------
*/
$subject = "General";

$topicLower = strtolower($topic);

if(

    str_contains($topicLower,'math') ||
    str_contains($topicLower,'mathematics') ||
    str_contains($topicLower,'algebra') ||
    str_contains($topicLower,'fraction') ||
    str_contains($topicLower,'equation') ||
    str_contains($topicLower,'geometry') ||
    str_contains($topicLower,'calculus') ||
    str_contains($topicLower,'number') ||
    str_contains($topicLower,'decimal') ||
    str_contains($topicLower,'percentage') ||
    str_contains($topicLower,'ratio') ||
    str_contains($topicLower,'probability') ||
    str_contains($topicLower,'statistics') ||
    str_contains($topicLower,'graph') ||
    str_contains($topicLower,'triangle') ||
    str_contains($topicLower,'circle') ||
    str_contains($topicLower,'area') ||
    str_contains($topicLower,'volume') ||
    str_contains($topicLower,'multiply') ||
    str_contains($topicLower,'division')

){
    $subject = "Math";
}

elseif(

    str_contains($topicLower,'science') ||
    str_contains($topicLower,'physics') ||
    str_contains($topicLower,'chemistry') ||
    str_contains($topicLower,'biology') ||
    str_contains($topicLower,'newton') ||
    str_contains($topicLower,'force') ||
    str_contains($topicLower,'motion') ||
    str_contains($topicLower,'energy') ||
    str_contains($topicLower,'atom') ||
    str_contains($topicLower,'molecule') ||
    str_contains($topicLower,'cell') ||
    str_contains($topicLower,'gravity') ||
    str_contains($topicLower,'planet') ||
    str_contains($topicLower,'solar system') ||
    str_contains($topicLower,'electricity') ||
    str_contains($topicLower,'magnet') ||
    str_contains($topicLower,'ecosystem') ||
    str_contains($topicLower,'human body') ||
    str_contains($topicLower,'photosynthesis') ||
    str_contains($topicLower,'temperature') ||
    str_contains($topicLower,'experiment')

){
    $subject = "Science";
}

elseif(

    str_contains($topicLower,'english') ||
    str_contains($topicLower,'grammar') ||
    str_contains($topicLower,'verb') ||
    str_contains($topicLower,'noun') ||
    str_contains($topicLower,'adjective') ||
    str_contains($topicLower,'adverb') ||
    str_contains($topicLower,'pronoun') ||
    str_contains($topicLower,'sentence') ||
    str_contains($topicLower,'paragraph') ||
    str_contains($topicLower,'essay') ||
    str_contains($topicLower,'writing') ||
    str_contains($topicLower,'reading') ||
    str_contains($topicLower,'vocabulary') ||
    str_contains($topicLower,'spelling') ||
    str_contains($topicLower,'synonym') ||
    str_contains($topicLower,'antonym') ||
    str_contains($topicLower,'tense') ||
    str_contains($topicLower,'punctuation') ||
    str_contains($topicLower,'comprehension') ||
    str_contains($topicLower,'language')

){
    $subject = "English";
}

elseif(

    str_contains($topicLower,'computer') ||
    str_contains($topicLower,'programming') ||
    str_contains($topicLower,'coding') ||
    str_contains($topicLower,'php') ||
    str_contains($topicLower,'html') ||
    str_contains($topicLower,'css') ||
    str_contains($topicLower,'javascript') ||
    str_contains($topicLower,'java') ||
    str_contains($topicLower,'python') ||
    str_contains($topicLower,'database') ||
    str_contains($topicLower,'sql') ||
    str_contains($topicLower,'algorithm') ||
    str_contains($topicLower,'software') ||
    str_contains($topicLower,'hardware') ||
    str_contains($topicLower,'network') ||
    str_contains($topicLower,'internet') ||
    str_contains($topicLower,'ai') ||
    str_contains($topicLower,'artificial intelligence') ||
    str_contains($topicLower,'machine learning') ||
    str_contains($topicLower,'cybersecurity')

){
    $subject = "Computer Science";
}
/*
|--------------------------------------------------------------------------
| Insert Result
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO quiz_results
    (
        user_id,
        topic,
        subject,
        score,
        total_questions
    )
    VALUES
    (
        ?, ?, ?, ?, ?
    )"
);

mysqli_stmt_bind_param(
    $stmt,
    "issii",
    $user_id,
    $topic,
    $subject,
    $score,
    $totalQuestions
);

mysqli_stmt_execute($stmt);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: result.php");
exit;