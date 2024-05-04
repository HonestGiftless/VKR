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
$notes = $_POST['new_notes'];

$sql = "UPDATE groups SET NOTES = '$notes' WHERE id = $groupId AND user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);