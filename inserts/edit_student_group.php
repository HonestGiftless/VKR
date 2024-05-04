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
$oldGroupId = $_POST['currentGroupId'];
$newGroupId = $_POST['group_id'];

$sqlG = "SELECT * FROM groups WHERE id = '$newGroupId' AND user_id = " . $_SESSION['user_id'];
$resultG = $conn->query($sqlG);
$groupName = $resultG->fetch_assoc();
$groupName = $groupName["group_name"];

$sql = "UPDATE students SET group_num = '$groupName', group_id = '$newGroupId' WHERE id = '$studentId' AND user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);

$sqlTask = "SELECT * FROM students WHERE user_id = " . $_SESSION['user_id'];
$resultTask = $conn->query($sqlTask);

if ($resultTask->num_rows > 0) {
    while ($rowT = $resultTask->fetch_assoc()) {
        if ($rowT["group_num"] != "-") {
            $grades = str_replace("*", "-", $rowT["grades"]);
            $changeSql = "UPDATE students SET grades = '$grades' WHERE id = '" . $rowT["id"] . "' AND user_id = " . $_SESSION['user_id'];
            $resultChange = $conn->query($changeSql);
        }
    }
}