<?php

session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $direct = $_POST["direct"];
    $deadline = $_POST["deadline"];
    $complex = $_POST["complex"];
    $hasGroup = $_POST["has_group"];
    $currentDate = date("Y-m-d");

    if ($hasGroup) {
        $hasGroup = 1;
    } else {
        $hasGroup = 0;
    }

    $sql = "INSERT INTO tasks (name, direct, deadline, complex, has_group, add_data, user_id) VALUES ('$name', '$direct', '$deadline', '$complex', '$hasGroup', '$currentDate', '" . $_SESSION['user_id'] . "')";

    $sqlGradess = "SELECT * FROM students WHERE user_id = " . $_SESSION['user_id'];
    $resultGradess = $conn->query($sqlGradess);

    if ($resultGradess->num_rows > 0) {
        while ($rowG = $resultGradess->fetch_assoc()) {
            if ($rowG["grades"] != "") {
                if ($rowG["group_num"] != "-" && $hasGroup == 1) {
                    $rowG["grades"] .= ", -";
                    $resultSQL = "UPDATE students SET grades = '" . $rowG["grades"] . "' WHERE id = '" . $rowG["id"] . "' AND user_id = " . $_SESSION['user_id'];
                    $connect = $conn->query($resultSQL);
                } else if ($rowG["group_num"] == "-" && $hasGroup == 1) {
                    $rowG["grades"] .= ", *";
                    $resultSQL = "UPDATE students SET grades = '" . $rowG["grades"] . "' WHERE id = '" . $rowG["id"] . "' AND user_id = " . $_SESSION['user_id'];
                    $connect = $conn->query($resultSQL);
                } else if ($rowG["group_num"] != "-" && $hasGroup == 0) {
                    $rowG["grades"] .= ", -";
                    $resultSQL = "UPDATE students SET grades = '" . $rowG["grades"] . "' WHERE id = '" . $rowG["id"] . "' AND user_id = " . $_SESSION['user_id'];
                    $connect = $conn->query($resultSQL);
                } else if ($rowG["group_num"] == "-" && $hasGroup == 0) {
                    $rowG["grades"] .= ", -";
                    $resultSQL = "UPDATE students SET grades = '" . $rowG["grades"] . "' WHERE id = '" . $rowG["id"] . "' AND user_id = " . $_SESSION['user_id'];
                    $connect = $conn->query($resultSQL);
                }
            } else {
                if ($rowG["group_num"] != "-" && $hasGroup == 1) {
                    $rowG["grades"] .= "-";
                    $resultSQL = "UPDATE students SET grades = '" . $rowG["grades"] . "' WHERE id = '" . $rowG["id"] . "' AND user_id = " . $_SESSION['user_id'];
                    $connect = $conn->query($resultSQL);
                } else if ($rowG["group_num"] == "-" && $hasGroup == 1) {
                    $rowG["grades"] .= "*";
                    $resultSQL = "UPDATE students SET grades = '" . $rowG["grades"] . "' WHERE id = '" . $rowG["id"] . "' AND user_id = " . $_SESSION['user_id'];
                    $connect = $conn->query($resultSQL);
                } else if ($rowG["group_num"] != "-" && $hasGroup == 0) {
                    $rowG["grades"] .= "-";
                    $resultSQL = "UPDATE students SET grades = '" . $rowG["grades"] . "' WHERE id = '" . $rowG["id"] . "' AND user_id = " . $_SESSION['user_id'];
                    $connect = $conn->query($resultSQL);
                } else if ($rowG["group_num"] == "-" && $hasGroup == 0) {
                    $rowG["grades"] .= "-";
                    $resultSQL = "UPDATE students SET grades = '" . $rowG["grades"] . "' WHERE id = '" . $rowG["id"] . "' AND user_id = " . $_SESSION['user_id'];
                    $connect = $conn->query($resultSQL);
                }
            }
        }
    }

    if ($conn->query($sql)) {
        header("Location: ../tasks.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}



$conn->close();