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

$taskSql = "SELECT * FROM tasks WHERE user_id = $user_id";
$resultTask = $conn->query($taskSql);

$hasStudents = true;
$hasTasks = true;

$tasksArr = [];
$filteredKeys = [];

if ($resultTask->num_rows > 0) {
    $hasTasks = true;
    while ($rowTasks = $resultTask->fetch_assoc()) {
        if ($rowTasks["has_group"] == '0') {
            $tasksArr[$rowTasks["id"] . "-" . $rowTasks["name"]] = 0;
        }
    }
} else {
    $hasTasks = false;
}

foreach ($tasksArr as $key => $value) {
    // Используем регулярное выражение для извлечения цифр из начала ключа
    preg_match('/^\d+/', $key, $matches);
    if (!empty($matches)) {
        $filteredKey = $matches[0]; // Получаем первое найденное совпадение (цифры в начале строки)
        $filteredKeys[$filteredKey] = $value;
    }
}


foreach($filteredKeys as $key=>$value) {
    $taskSqlSec = "SELECT * FROM tasks WHERE id = $key AND user_id = $user_id";
    $resultTaskSec = $conn->query($taskSqlSec);

    if ($resultTaskSec->num_rows > 0) {
        while ($rwT = $resultTaskSec->fetch_assoc()) {
            $done = [];
            $arr = explode(", ", $rwT["done_count_id"]);
            $arr = array_map("trim", $arr);
            $arr = array_diff($arr, array(''));
            foreach($arr as $k=>$v) {
                $sqlSec = "SELECT name FROM students WHERE id = $v AND user_id = $user_id";
                $resultSec = $conn->query($sqlSec);
                if ($resultSec->num_rows > 0) {
                    $rs = $resultSec->fetch_assoc();
                    $done[$rs["name"]] = 1;
                } else {
                    $hasStudents = false;
                }
            }
        }
    }
    $filteredKeys[$key] = $done;
}

if ($hasStudents && $hasTasks) {
    foreach ($filteredKeys as $key=>$value) {
        foreach ($tasksArr as $k=>$v) {
            if (strstr($k, $key)) {
                $tasksArr[$k] = $filteredKeys[$key];
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($tasksArr, JSON_UNESCAPED_UNICODE);
} else {
    echo "NO";
}