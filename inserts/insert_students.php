<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $group = $_POST["group"];
    $age = $_POST["age"];
    $notes = $_POST["notes"];

    $sqlGroup = "SELECT * FROM groups WHERE id = '$group' AND user_id = " . $_SESSION["user_id"];
    $resultGroup = $conn->query($sqlGroup);
    $nameOfGroup = $resultGroup->fetch_assoc();
    $nameOfGroup = $nameOfGroup["group_name"];

    $sqlTask = "SELECT * FROM tasks WHERE user_id = " . $_SESSION["user_id"];
    $resultTask = $conn->query($sqlTask);

    if ($resultTask->num_rows > 0) {
        $string = '';
        while ($rowT = $resultTask->fetch_assoc()) {
            if ($string == '') {
                if ($rowT["has_group"] == "1" && $nameOfGroup != "-") {
                    $string .= '-';
                } else if ($rowT["has_group"] == "1" && $nameOfGroup == "-") {
                    $string .= "*";
                } else {
                    $string .= "-";
                }
            } else {
                if ($rowT["has_group"] == "1" && $nameOfGroup != "-") {
                    $string .= ', -';
                } else if ($rowT["has_group"] == "1" && $nameOfGroup == "-") {
                    $string .= ", *";
                } else {
                    $string .= ", -";
                }
            }
        }
    }

    $sqlDates = "SELECT * FROM attendance WHERE user_id = " . $_SESSION["user_id"];
    $resultDates = $conn->query($sqlDates);
    $datesCount = $resultDates->num_rows;

    if ($datesCount > 0) {
        $datesString = '';

        for ($i = 0; $i < $datesCount; $i++) {
            if ($datesString == '') {
                $datesString .= '-';
            } else {
                $datesString .= ', -';
            }
        }
    } else {
        $datesString = '';
    }

    $sql = "INSERT INTO students (name, group_num, age, notes, grades, dates, group_id, user_id) VALUES ('$name', '$nameOfGroup', '$age', '$notes', '$string', '$datesString', '$group', '" . $_SESSION["user_id"] . "')" ;
    if ($conn->query($sql)) {
        header("Location: ../index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();