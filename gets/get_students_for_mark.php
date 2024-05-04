<?php

session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$groupId = isset($_GET['group_id']) ? $_GET['group_id'] : null;

$sql_query = "SELECT * FROM students WHERE group_id = '$groupId' AND user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql_query);
$response = array();

$html = "";

if ($result->num_rows > 0) {
    $studentsHTML = "";
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $studentId = $row["id"];
        $studentsHTML .= "<div class='form-container'><label for='student" . $i . "'>Какие навыки проявил " . $row["name"] . "?</label><input type='text' name='" . $row["id"] . "' class='student" . $i . "'></input></div>";
        $i += 1;
    }
    $studentsHTML .= "<input type='hidden' name='hidden_group' value='" . $groupId . "'>";
    $response['studentsHTML'] = $studentsHTML;
    $response['hasStudents'] = true;
} else {
    $response['studentsHTML'] = "<p id='zero_student'>Детей в данной группе нет</p>";
    $response['hasStudents'] = false;
}

echo json_encode($response);

$conn->close();