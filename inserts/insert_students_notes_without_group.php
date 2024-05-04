<?php

session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$taskId = isset($_POST['task_id']) ? $_POST['task_id'] : null;
$user_id = $_SESSION['user_id'];

$sql_q = "SELECT * FROM tasks WHERE id =  $taskId AND user_id = " . $_SESSION['user_id'];
$result_q = $conn->query($sql_q);

if (!$result_q) {
    echo "Ошибка выполнения запроса: " . $conn->error;
} else {
    while ($row = $result_q->fetch_assoc()) {
        $value = '';
        $id_value = '';

        $arr = explode(",", $row["done_count"]);
        $arr = array_map("trim", $arr);

        $id_values_array = array();

        if ($row["done_count_id"] == '0') {
            foreach ($_POST as $key => $val) {
                if (is_numeric($key) && $val != '') {
                    $id_values_array[] = $key;
                }
            }

            for ($i = 0; $i < count($id_values_array); $i++) {
                if ($i != count($id_values_array) - 1) {
                    $id_value .= $id_values_array[$i] . ", ";
                } else {
                    $id_value .= $id_values_array[$i];
                }
            }

            $sql_s = "UPDATE tasks SET done_count_id = '$id_value' WHERE id = $taskId AND user_id = " . $_SESSION['user_id'];
            $res_s = $conn->query($sql_s);

            foreach ($_POST as $key => $val) {
                if (is_numeric($key) && $val != '') {
                    $sql = "SELECT * FROM students WHERE id = $key AND user_id = " . $_SESSION['user_id'];
                    $result = $conn->query($sql);
                    $result_arr = $result->fetch_assoc();

                    if ($result_arr["skills"] == "") {
                        $text = $val;
                    } else {
                        $text = $result_arr["skills"] . ", " . $val;
                    }

                    $sql = "UPDATE students SET skills = '$text' WHERE id = $key AND user_id = " . $_SESSION['user_id'];
                    $result = $conn->query($sql);
                }
            }

            $vl = '';
            for ($i = 0; $i < count($id_values_array); $i++) {
                $sql_query = "SELECT * FROM students WHERE id = " . $id_values_array[$i] . " AND user_id = " . $_SESSION['user_id'];
                $result_query = $conn->query($sql_query);
                if ($result_query->num_rows > 0) {
                    while ($row_query = $result_query->fetch_assoc()) {
                        if ($row_query["id"] != $id_values_array[count($id_values_array) - 1]) {
                            $vl .= $row_query["name"] . ", ";
                        } else {
                            $vl .= $row_query["name"];
                        }
                    }
                }
            }
            
            $sql = "UPDATE tasks SET done_count = '$vl' WHERE id = $taskId AND user_id = " . $_SESSION['user_id'];
            $result = $conn->query($sql);
        } else {
            foreach ($_POST as $key => $val) {
                if (is_numeric($key) && $val != '') {
                    $id_values_array[] = $key;
                }
            }

            $id_arr = explode(",", $row["done_count_id"]);
            $id_arr = array_map("trim", $id_arr);

            foreach ($id_values_array as $val) {
                if (!in_array(strval($val), $id_arr)) {
                    $id_arr[] = strval($val);
                }
            }

            for ($i = 0; $i < count($id_arr); $i++) {
                if ($i != count($id_arr) - 1) {
                    $id_value .= $id_arr[$i] . ", ";
                } else {
                    $id_value .= $id_arr[$i];
                }
            }

            $sql_s = "UPDATE tasks SET done_count_id = '$id_value' WHERE id = $taskId AND user_id = " . $_SESSION['user_id'];
            $res_s = $conn->query($sql_s);

            foreach ($_POST as $key => $val) {
                if (is_numeric($key) && $val != '') {
                    $sql = "SELECT * FROM students WHERE id = $key";
                    $result = $conn->query($sql);
                    $result_arr = $result->fetch_assoc();

                    $text = $result_arr["skills"] . ", " . $val;
                    $sql = "UPDATE students SET skills = '$text' WHERE id = $key";
                    $result = $conn->query($sql);
                }
            }

            $vl = '';
            for ($i = 0; $i < count($id_arr); $i++) {
                $sql_query = "SELECT * FROM students WHERE id = " . $id_arr[$i] . " AND user_id = " . $_SESSION['user_id'];
                $result_query = $conn->query($sql_query);
                if ($result_query->num_rows > 0) {
                    while ($row_query = $result_query->fetch_assoc()) {
                        if ($row_query["id"] != $id_arr[count($id_arr) - 1]) {
                            $vl .= $row_query["name"] . ", ";
                        } else {
                            $vl .= $row_query["name"];
                        }
                    }
                }
            }
            $sql = "UPDATE tasks SET done_count = '$vl' WHERE id = $taskId AND user_id = " . $_SESSION['user_id'];
            $result = $conn->query($sql);
        }
    }
    $ss = "SELECT * FROM groups WHERE user_id = $user_id";
    $resSs = $conn->query($ss);

    if ($resSs->num_rows > 0) {
        while ($rwSs = $resSs->fetch_assoc()) {
            $newSk = '';
            $getGroupSs = "SELECT * FROM students WHERE group_id = " . $rwSs["id"] . " AND user_id = $user_id";
            $resGet = $conn->query($getGroupSs);
            if ($resGet->num_rows > 0) {
                while ($rwGg = $resGet->fetch_assoc()) {
                    $newSk .= $rwGg["skills"] . ", ";
                }
            }
            $newSkArr = explode(", ", $newSk);
            $newSkArr = array_map("trim", $newSkArr);
            $newSkArr = array_diff($newSkArr, array(''));
            $resSk = implode(", ", $newSkArr);

            $inserts = "UPDATE groups SET skills = '$resSk' WHERE id = " . $rwSs["id"] . " AND user_id = $user_id";
            $con = $conn->query($inserts);
        }
    }
    
    header("Location: ../tasks.php");
}