<?php
    session_start();
    require_once '../database/database.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $sqlS = "SELECT * FROM students WHERE user_id = $user_id";
    $resultS = $conn->query($sqlS);
    
    if ($resultS->num_rows > 0) {
        $studentsInGroup = 0;
        $studentsNotInGroup = 0;

        $hasStudent = true;

        while ($rowS = $resultS->fetch_assoc()) {
            if ($rowS["group_num"] != '-') {
                $studentsInGroup++;
            } else {
                $studentsNotInGroup++;
            }
        }

        $data = array(
            'all' => $resultS->num_rows,
            'inGroup' => $studentsInGroup,
            'notInGroup' => $studentsNotInGroup
        );

        if ($data['all'] == 0) {
            $hasStudent = false;
        }

        if ($hasStudent == true) {
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            echo "NO";
        }
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Ошибка запроса к базе данных'));
    }  
    