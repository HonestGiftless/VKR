<?php
session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM students WHERE user_id = $user_id";
$result = $conn->query($sql);

$students = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row["name"];
    }
}

header('Content-Type: application/json');
echo json_encode($students, JSON_UNESCAPED_UNICODE);