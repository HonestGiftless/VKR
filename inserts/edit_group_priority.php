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
$priorityName = $_POST['priority_name'];

$sql = "UPDATE groups SET priority = '$priorityName' WHERE id = $groupId AND user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);