<?php

session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$studentId = $_POST['student_id'];
$markId = $_POST['mark_index'];
$markValue = $_POST['mark'];

if ($markValue == '+') {
    $resultString = 'Н';
} else {
    $resultString = '-';
}

$sql = "SELECT * FROM students WHERE id = $studentId AND user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $marksArr = explode(", ", $row["dates"]);
        $marksArr = array_replace($marksArr, [$markId => $resultString]);
        $data = implode(", ", $marksArr);
    }
}

$updateSQL = "UPDATE students SET dates = '$data' WHERE id = $studentId AND user_id = " . $_SESSION['user_id'];
$updateResult = $conn->query($updateSQL);