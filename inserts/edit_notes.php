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
$notes = $_POST['notes_value'];

$sql = "UPDATE students SET notes = '$notes' WHERE id = '$studentId' AND user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);