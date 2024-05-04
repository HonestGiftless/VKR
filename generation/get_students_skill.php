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

$hasStudents;
$hasSkills;

$resultArr = [];
$filteredKeys = [];

if ($result->num_rows > 0) {
    $hasStudents = true;
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $done = [];

        $arr = explode(", ", $row["skills"]);
        $arr = array_map("trim", $arr);
        $arr = array_diff($arr, array(''));

        foreach ($arr as $val) {
            $skill = mb_strtolower($val, 'UTF-8');

            if (array_key_exists($skill, $done)) {
                $done[$skill]++;
            } else {
                $done[$skill] = 1;
            }
        }
        $resultArr[$row["name"] . strval($i)] = $done;
        if (count($resultArr) > 0) {
            $hasSkills = true;
        } else {
            $hasSkills = false;
        }
        $i++;
    }
} else {
    $hasStudents = false;
}

if ($hasStudents && $hasSkills) {
    header('Content-Type: application/json');
    echo json_encode($resultArr, JSON_UNESCAPED_UNICODE);
} else {
    echo "NO";
}