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

if ($result->num_rows > 0) {
    $hasGrade = true;

    $students = array(); // Для учеников (значения - оценки)


    while ($row = $result->fetch_assoc()) {
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

        $arr = explode(", ", $row["grades"]);
        $arr = array_map("trim", $arr);
        $arr = array_diff($arr, array(''));

        $counter = array_count_values($arr);
        for ($i = 1; $i < 6; $i++) {
            $grades[strval($i)] += ($counter[strval($i)] ?? 0);
        }

        $newGrades = array();

        foreach ($grades as $key => $value) {
            if ($value !== 0) {
                $newKey = $numberToWord[$key];
                $newGrades[$newKey] = $value;
            }
        }

        $students[$row["name"]] = $newGrades;
    }

    header('Content-Type: application/json');
    echo json_encode($students, JSON_UNESCAPED_UNICODE);
}