<?php

session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

function getDatesBetween($start_date, $end_date) {
    $dates = array();
    $start_date = strtotime($start_date);
    $end_date = strtotime($end_date);

    while ($start_date <= $end_date) {
        $dates[] = date('Y-m-d', $start_date);
        $start_date = strtotime('+1 day', $start_date);
    }

    return $dates;
}

$days_per_week = $_POST['days_per_week'];
$start_date = new DateTime($_POST['start_date']);
$end_date = new DateTime($_POST['end_date']);
$selected_weekdays = $_POST['selected_weekdays'];

$weekday_map = [
    'Понедельник' => 'Monday',
    'Вторник' => 'Tuesday',
    'Среда' => 'Wednesday',
    'Четверг' => 'Thursday',
    'Пятница' => 'Friday',
    'Суббота' => 'Saturday',
    'Воскресенье' => 'Sunday'
];

$selected_date = array();
foreach ($weekday_map as $rus_day => $eng_day) {
    if (in_array($rus_day, $selected_weekdays)) {
        $selected_date[] = $eng_day;
    }
}

$interval = new DateInterval('P1D');
$date_range = new DatePeriod($start_date, $interval, $end_date);

$result_date = array();
foreach ($date_range as $date) {
    if (in_array($date->format('l'), $selected_date)) {
        foreach ($weekday_map as $rus_day => $eng_day) {
            if ($date->format('l') == $eng_day) {
                $result_date[] = $date->format('Y-m-d');
            }
        }
    }
}

foreach ($result_date as $day) {
    $sql = "INSERT INTO attendance (date, user_id) VALUES ('$day', '" . $_SESSION['user_id'] . "')";
    if ($conn->query($sql) === false) {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

$sqlResult = "SELECT * FROM students WHERE user_id = " . $_SESSION['user_id'];
$resultQuery = $conn->query($sqlResult);

if ($resultQuery->num_rows > 0) {
    $sqlDates = "SELECT * FROM attendance WHERE user_id = " . $_SESSION['user_id'];
    $resultDates = $conn->query($sqlDates);
    $datesCount = $resultDates->num_rows;
    while ($resultRow = $resultQuery->fetch_assoc()) {
        if ($resultRow["dates"] == "") {
            $datesString = '';
            for ($i = 0; $i < $datesCount; $i++) {
                if ($datesString == '') {
                    $datesString .= '-';
                } else {
                    $datesString .= ', -';
                }
            }
            $updateSql = "UPDATE students SET dates = '$datesString' WHERE id = " . $resultRow["id"] . " AND user_id = " . $_SESSION['user_id'];
            $updateQuery = $conn->query($updateSql);
        }
    }
}

$conn->close();

header("Location: ../journal.php");