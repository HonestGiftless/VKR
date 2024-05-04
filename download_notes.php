<?php

session_start();
require_once 'database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['student_id'])) {
    $studentId = $_GET['student_id'];

    $sql = "SELECT notes FROM students WHERE id = $studentId AND user_id = " . $_SESSION['user_id'];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $file = fopen("notes.txt", "w");
        $row = $result->fetch_assoc();
        fwrite($file, $row['notes']);
        fclose($file);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename('notes.txt').'"');
        readfile('notes.txt');

        unlink('notes.txt');
    } else {
        echo "Заметок у данного ученика пока нет";
    }

    $conn->close();
} else {
    echo "Ошибка";
}