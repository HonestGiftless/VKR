<?php

session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$taskId = isset($_POST['task_id']) ? $_POST['task_id'] : null;
$groupId = isset($_POST['hidden_group']) ? $_POST['hidden_group'] : null;

$sql_task = "SELECT * FROM tasks WHERE id = $taskId AND user_id = $user_id";
$result_task = $conn->query($sql_task);
$arr = $result_task->fetch_assoc();

if ($arr['done_count'] == '0' && $arr["group_done_count_id"] == '0') {
    $getGroupName = "SELECT group_name FROM groups WHERE id = $groupId AND user_id = $user_id";
    $resultGroupName = $conn->query($getGroupName);
    $rowGroupName = $resultGroupName->fetch_assoc();
    $group_name = $rowGroupName["group_name"] . ",";
    $insertsGroupId = $groupId . ",";
    $sqlInsert = "UPDATE tasks SET done_count = '$group_name', group_done_count_id = '$insertsGroupId' WHERE id = $taskId AND user_id = $user_id";
    $resultInsert = $conn->query($sqlInsert);
} else {
    $approvedGroupsIds = explode(",", $arr["group_done_count_id"]);
    $approvedGroupsIds = array_diff($approvedGroupsIds, array(''));

    $approvedGroupsNames = explode(",", $arr["done_count"]);
    $approvedGroupsNames = array_diff($approvedGroupsNames, array(''));

    $getGroupName = "SELECT group_name FROM groups WHERE id = $groupId AND user_id = $user_id";
    $resultGroupName = $conn->query($getGroupName);
    $rowGroupName = $resultGroupName->fetch_assoc();
    if (!in_array($groupId, $approvedGroupsIds)) {
        $approvedGroupsIds[] = $groupId;
        $approvedGroupsNames[] = $rowGroupName["group_name"];
    }

    $resNames = implode(",", $approvedGroupsNames);
    $resIds = implode(",", $approvedGroupsIds);

    $insertQuery = "UPDATE tasks SET done_count = '$resNames', group_done_count_id = '$resIds' WHERE id = $taskId AND user_id = $user_id";
    $resultIns = $conn->query($insertQuery);
}


foreach ($_POST as $key=>$value) {
    if (is_numeric($key)) {
        $patternComma = '/,/';
        $patternCommaSpace = '/,\s/';

        if (preg_match($patternComma, $value)) {
            $skills = explode(',', $value);
            $skills = array_map("trim", $skills);
        } else if (preg_match($patternCommaSpace, $value)) {
            $skills = explode(', ', $value);
            $skills = array_map("trim", $skills);
        } else {
            $skills[] = $value;
        }

        $skills = array_diff($skills, array(''));
        $newSkill = implode(", ", $skills);

        $getStudent = "SELECT * FROM students WHERE id = $key AND user_id = $user_id";
        $resGetS = $conn->query($getStudent);
        $studentArr = $resGetS->fetch_assoc();
        $studSkill = $studentArr["skills"];

        if ($studSkill == '') {
            $insertNewSkills = "UPDATE students SET skills = '$newSkill' WHERE id = $key AND user_id = $user_id";
        } else {
            $resString = $studSkill . ", " . $newSkill;
            $insertNewSkills = "UPDATE students SET skills = '$resString' WHERE id = $key AND user_id = $user_id";
        }
        $resultInsertNewSkill = $conn->query($insertNewSkills);
        $skills = array();
    }
}

$rs = '';

$sqlG = "SELECT * FROM students WHERE group_id = $groupId AND user_id = $user_id";
$resultG = $conn->query($sqlG);

if ($resultG->num_rows > 0) {
    while ($rwG = $resultG->fetch_assoc()) {
        $rs .= $rwG["skills"] . ", ";
    }
}

$generalSkills = explode(",", $rs);
$generalSkills = array_map("trim", $generalSkills);
$generalSkills = array_diff($generalSkills, array(''));

$getGroup = "SELECT * FROM groups WHERE id = $groupId AND user_id = $user_id";
$gets = $conn->query($getGroup);
$arrNeededGroup = $gets->fetch_assoc();

if ($arrNeededGroup["skills"] == '') {
    $insSk = "UPDATE groups SET skills = '" . implode(", ", $generalSkills) . "' WHERE id = $groupId AND user_id = $user_id";
} else {
    $newSk = array();
    foreach ($_POST as $key=>$value) {
        if (is_numeric($key)) {
            $generalSkills[] = $value;
        }
    }
    $insSk = "UPDATE groups SET skills = '" . implode(", ", $generalSkills) . "' WHERE id = $groupId AND user_id = $user_id";
}

$insNew = $conn->query($insSk);

header("Location: ../tasks.php");