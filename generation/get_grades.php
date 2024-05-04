<?php
session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT grades FROM students WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $hasGrade = true;

    $grades = [
        '1' => 0,
        '2' => 0,
        '3' => 0,
        '4' => 0,
        '5' => 0
    ];

    $numberToWord = [
        '1' => 'one',
        '2' => 'two',
        '3' => 'three',
        '4' => 'four',
        '5' => 'five'
    ];

    while ($row = $result->fetch_assoc()) {
        $arr = explode(", ", $row["grades"]);
        $arr = array_map("trim", $arr);
        $arr = array_diff($arr, array(''));

        $counter = array_count_values($arr);

        for ($i = 1; $i < 6; $i++) {
            $grades[strval($i)] += ($counter[strval($i)] ?? 0);
        }
    }


    for ($i = 1; $i < 6; $i++) {
        if ($grades[strval($i)] == 0) {
            $hasGrade = false;
            break;
        }
    }

    $newGrades = [];

    foreach ($grades as $key => $value) {
        $newKey = $numberToWord[$key];
        $newGrades[$newKey] = $value;
    }

    if ($hasGrade == true) {
        header('Content-Type: application/json');
        echo json_encode($newGrades);
    } else {
        echo "NO";
    }
} else {
    http_response_code(500);
    echo json_encode(array('error' => 'Ошибка запроса к базе данных'));
}