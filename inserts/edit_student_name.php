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
$studentName = $_POST['grade_value'];

$sqlStudent = "SELECT * FROM students WHERE id = '$studentId' AND user_id = " . $_SESSION["user_id"];
$resultStudents = $conn->query($sqlStudent);

$sql = "UPDATE students SET name = '$studentName' WHERE id = $studentId AND user_id = " . $_SESSION["user_id"];
$result = $conn->query($sql);