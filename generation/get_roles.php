<?php
session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

function toLower($str) {
    return strtolower($str);
}

$hasStudents;
$hasRoles;

$sql = "SELECT * FROM students WHERE user_id = $user_id";
$result = $conn->query($sql);

$studentsArr = array();

$rolesQuery = "SELECT * FROM roles";
$resultRoles = $conn->query($rolesQuery);
$rolesArr = $resultRoles->fetch_assoc();

$liderSkills = explode(", ", $rolesArr["lider"]);
$liderSkills = array_map("trim", $liderSkills);
$liderSkills = array_diff($liderSkills, array(''));

$motivatorSkills = explode(", ", $rolesArr["motivator"]);
$motivatorSkills = array_map("trim", $motivatorSkills);
$motivatorSkills = array_diff($motivatorSkills, array(''));

$executorSkills = explode(", ", $rolesArr["executor"]);
$executorSkills = array_map("trim", $executorSkills);
$executorSkills = array_diff($executorSkills, array(''));

$coordinatorSkills = explode(", ", $rolesArr["coordinator"]);
$coordinatorSkills = array_map("trim", $coordinatorSkills);
$coordinatorSkills = array_diff($coordinatorSkills, array(''));

if ($result->num_rows > 0) {
    $hasStudents = true;
    while ($row = $result->fetch_assoc()) {
        if ($row["skills"] != '') {
            $done = [
                'lider' => 0,
                'motivator' => 0,
                'executor' => 0,
                'coordinator' => 0
            ];
    
            $arr = explode(", ", $row["skills"]);
            $arr = array_map("trim", $arr);
            $arr = array_diff($arr, array(''));

            $arr = array_map(function($str) {
                return mb_strtolower($str, 'UTF-8');
            }, $arr);

            foreach ($arr as $val) {
                if (in_array($val, $liderSkills)) {
                    $done["lider"] += 1;
                } else if (in_array($val, $motivatorSkills)) {
                    $done["motivator"] += 1;
                } else if (in_array($val, $executorSkills)) {
                    $done["executor"] += 1;
                } else if (in_array($val, $coordinatorSkills)) {
                    $done["coordinator"] += 1;
                }
            }
    
            $studentsArr[$row["name"]] = $done;
        }
    }

    $resultArr = [];

    foreach($studentsArr as $key=>$value) {
        $maxValue = max($value);

        if ($maxValue > 0) {
            $maxKeys = array_keys($value, $maxValue);
            $resultArr[$key] = implode(", ", $maxKeys);
        }
    }

    if (count($resultArr) != 0) {
        $hasRoles = true;
    } else {
        $hasRoles = false;
    }
} else {
    $hasStudents = false;
}

if ($hasStudents && $hasRoles) {
    header('Content-Type: application/json');
    echo json_encode($resultArr, JSON_UNESCAPED_UNICODE);
} else {
    echo "NO";
}