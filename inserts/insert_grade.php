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
$gradeIndex = $_POST['grade_index'];
$grade = $_POST['grade'];

$sqlStudents = "SELECT * FROM students WHERE id = $studentId AND user_id = " . $_SESSION['user_id'];
$sqlTasks = "SELECT * FROM tasks WHERE user_id = " . $_SESSION['user_id'];

$resultStudents = $conn->query($sqlStudents);
$resultTasks = $conn->query($sqlTasks);

if ($resultStudents->num_rows > 0) {
    while ($rowStudent = $resultStudents->fetch_assoc()) {
        if ($grade >= 1 && $grade <= 5) {
            $gradesArr = explode(", ", $rowStudent["grades"]);
            $gradesArr = array_replace($gradesArr, [$gradeIndex => $grade]);
            $resultString = implode(", ", $gradesArr);
            
            $sql = "UPDATE students SET grades = '$resultString' WHERE id = $studentId AND user_id = " . $_SESSION['user_id'];
            $result = $conn->query($sql);
        } else {
            $gradesArr = explode(", ", $rowStudent["grades"]);
            $previousArr = $gradesArr;
            if ($rowStudent["group_num"] == "-") {
                $gradesArr = array_replace($gradesArr, [$gradeIndex => "*"]);
                $resultString = implode(", ", $gradesArr);
            } else {
                $gradesArr = array_replace($gradesArr, [$gradeIndex => "-"]);
                $resultString = implode(", ", $gradesArr);
            }
            $sql = "UPDATE students SET grades = '$resultString' WHERE id = $studentId AND user_id = " . $_SESSION['user_id'];
            $result = $conn->query($sql);
        }
    }
}