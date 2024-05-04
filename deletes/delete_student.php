<?php

session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$studentId = $_POST['student_id'];

$arr = explode(",", $studentId);

$sqlTask = "SELECT * FROM tasks WHERE user_id = $user_id";
$resultTask = $conn->query($sqlTask);

if ($resultTask->num_rows > 0) {
    while ($rowTask = $resultTask->fetch_assoc()) {
        $done_count_id = explode(", ", $rowTask["done_count_id"]);
        $diff_arr = array_diff($done_count_id, $arr);
        $resultDoneCount = implode(", ", $diff_arr);
    
        if (count($diff_arr) <= 0) {
            $sqlDoneCount = "UPDATE tasks SET done_count_id = 0 WHERE id = " . $rowTask["id"] . " AND user_id = $user_id";
        } else {
            $sqlDoneCount = "UPDATE tasks SET done_count_id = '$resultDoneCount' WHERE id =" . $rowTask["id"] . " AND user_id = $user_id";
        }
        $resultDoneCountQuery = $conn->query($sqlDoneCount);
    }
}

$sqlT = "SELECT * FROM tasks WHERE user_id = $user_id";
$resultT = $conn->query($sqlT);

if ($resultT->num_rows > 0) {
    while ($rwT = $resultT->fetch_assoc()) {
        $neededStudent = array();
        if ($rwT["done_count_id"] != '0') {
            $names = explode(", ", $rwT["done_count_id"]);
            foreach($names as $name) {
                $sqlNm = "SELECT name FROM students WHERE id = $name and user_id = $user_id";
                $rsNm = $conn->query($sqlNm);
                $rsNmG = $rsNm->fetch_assoc();
                $neededStudent[] = $rsNmG["name"];
            }
            $resultStudentsName = implode(", ", $neededStudent);
            $insertNames = "UPDATE tasks SET done_count = '$resultStudentsName' WHERE id = " . $rwT["id"] . " AND user_id = $user_id";
        } else {
            $insertNames = "UPDATE tasks SET done_count = 0 WHERE id = " . $rwT["id"] . " AND user_id = $user_id";
        }
        $resultInsert = $conn->query($insertNames);
    }
}

foreach($arr as $value) {
    $sql = "DELETE FROM students WHERE id = $value AND user_id = $user_id";
    $result = $conn->query($sql);
}

$selectGroups = "SELECT * FROM groups WHERE user_id = $user_id";
$resultGroups = $conn->query($selectGroups);

if ($resultGroups->num_rows > 0) {
    while ($rowGroup = $resultGroups->fetch_assoc()) {
        $takeCount = "SELECT * FROM students WHERE group_id = " . $rowGroup["id"] . " AND user_id = $user_id";
        $resultCount = $conn->query($takeCount);

        if ($resultCount->num_rows != 0) {
            $studentCount = $resultCount->num_rows;
            $skillsSt = array();
            while ($rwC = $resultCount->fetch_assoc()) {
                $skills[] = $rwC["skills"];
            }
            $resultSkill = implode(", ", $skills);
            $sqlUpGroup = "UPDATE groups SET students_count = $studentCount, skills = '$resultSkill' WHERE id = " . $rowGroup["id"] . " AND user_id = $user_id";
        } else {
            $sqlUpGroup = "UPDATE groups SET students_count = 0, priority = '-', skills = '-', NOTES = '-' WHERE id = " . $rowGroup["id"] . " AND user_id = $user_id";
        }
        $resC = $conn->query($sqlUpGroup);
    }
}