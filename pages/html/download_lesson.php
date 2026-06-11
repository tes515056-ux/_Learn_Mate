<?php

include '../../dblink.php';
require '../libs/fpdf/fpdf.php';

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$lesson_id = (int)$_GET['id'];

$query = mysqli_query($conn, "
    SELECT *
    FROM lessons
    WHERE id = $lesson_id
    LIMIT 1
");

$lesson = mysqli_fetch_assoc($query);

if (!$lesson) {
    die("Lesson not found");
}

// CREATE PDF
$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,$lesson['lesson_title'],0,1,'C');

$pdf->Ln(5);

// Description
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,8,"Description: " . $lesson['description']);

$pdf->Ln(5);

// Content
$pdf->SetFont('Arial','',11);
$pdf->MultiCell(0,6,strip_tags($lesson['content']));

// Output download
$fileName = preg_replace(
    '/[^A-Za-z0-9\- ]/',
    '',
    $lesson['lesson_title']
);

$pdf->Output(
    "D",
    $fileName . ".pdf"
);