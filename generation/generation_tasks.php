<?php
session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sqlT = "SELECT * FROM tasks WHERE user_id = $user_id";
$resultT = $conn->query($sqlT);

if ($resultT->num_rows > 0) {
    $groupTasks = 0;
    $notGroupTasks = 0;

    $hasTask = true;

    while ($rowT = $resultT->fetch_assoc()) {
        if ($rowT["has_group"] == '1') {
            $groupTasks++;
        } else {
            $notGroupTasks++;
        }
    }

    $dataTasks = array(
        'all' => $resultT->num_rows,
        'groupTask' => $groupTasks,
        'notGroupTask' => $notGroupTasks
    );

    foreach($dataTasks as $key=>$value) {
        if ($value == 0) {
            $hasTask = false;
            break;
        }
    }

    if ($hasTask == true) {
        header('Content-Type: application/json');
        echo json_encode($dataTasks);
    } else {
        echo "NO";
    }
} else {
    http_response_code(500);
    echo json_encode(array('error' => 'Ошибка запроса к базе данных'));
}