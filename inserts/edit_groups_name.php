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

$groupId = $_POST['group_id'];
$groupName = $_POST['group_name'];

$updateGroupForStudent = "UPDATE students SET group_num = '$groupName' WHERE group_id = $groupId AND user_id = " . $_SESSION['user_id'];
$updateResult = $conn->query($updateGroupForStudent);

$sql = "UPDATE groups SET group_name = '$groupName' WHERE id = $groupId AND user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);