<?php

session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = $_POST['group-name'];
    $group_priority = $_POST['group-priority'];
    $group_skills = $_POST['group-skills'];
    $group_notes = $_POST['group-notes'];

    $sql = "INSERT INTO groups (group_name, students_count, priority, skills, NOTES, user_id) VALUES ('$group_name', '0', '$group_priority', '$group_skills', '$group_notes', '" . $_SESSION['user_id'] . "')";

    if ($conn->query($sql)) {
        header("Location: ../groups.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();