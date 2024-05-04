<?php
    session_start();
    require_once 'database/database.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $taskId = isset($_GET['task_id']) ? $_GET['task_id'] : null;
    $user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отметка задания</title>
    <link rel="stylesheet" href="styles/navigation_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/marks_style.css">
</head>
<body>
    <div class="navigation_container">
        <div class="students_container">
            <a href="index.php">
                <img src="images/students.png" alt="Students Icon">
                <p>Ученики</p>
            </a>
            <a href="groups.php">
                <img src="images/groups.png" alt="Groups Icon">
                <p>Группы</p>
            </a>
            <a href="tasks.php">
                <img src="images/tasks.png" alt="Tasks Icon">
                <p>Задания</p>
            </a>
            <a href="grades.php">
                <img src="images/grades.png" alt="Grades Icon">
                <p>Оценки</p>
            </a>
            <a href="journal.php">
                <img src="images/journal.png" alt="Journal Icon">
                <p>Журнал</p>
            </a>
            <a href="statistic.php">
                <img src="images/statistic.png" alt="Statistics Icon">
                <p>Статистика</p>
            </a>
        </div>
        <div class="user_container">
            <a href="settings.php">
                <img src="images/settings.png" alt="Settings Icon">
                <p>Настройки</p>
            </a>
            <a href="logout.php">
                <img src="images/logout.png" alt="Logout Icon">
                <p>Выход</p>
            </a>
        </div>
    </div>
    <div class="main_container">
        <h2>Отметка задания</h2>
        <?php
            $sql_task = "SELECT group_done_count_id FROM tasks WHERE id = $taskId AND user_id = " . $_SESSION['user_id'];
            $result_task = $conn->query($sql_task);

            $row = $result_task->fetch_assoc();
            $res_arr = explode(",", $row["group_done_count_id"]);
            $result_task->close();
            
            $new_array = array_diff($res_arr, array(''));
            $new_array = array_map("trim", $new_array);
            $result_arr = array();
            
            foreach ($new_array as $value) {
                if ($value != "") {
                    $result_arr[] = $value;
                }
            }
        ?>
        <form action="" id="myForm">
            <div class="form-container">
                <select name="groupSelect" id="groupSelect" onchange="getStudents()">
                    <?php
                        $sql = "SELECT * FROM groups WHERE user_id = " . $_SESSION['user_id'];
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($row["group_name"] != "-" && !in_array($row["id"], $result_arr)) {
                                    echo "<option value='" . $row["id"] . "'>" . $row["group_name"] . "</option>";
                                }
                            }
                        }
                    ?>
                </select>
            </div>
        </form>
        <h3 id="descripe_header">Пожалуйста, указывайте навыки через запятую!</h3>
        <form action="inserts/insert_students_notes.php" method="POST">
            <div id="studentList"></div>
            <input type="hidden" name="task_id" value="<?=$taskId?>">
            <div class="form-container">
                <input type="submit" value="Отправить" id="send">
            </div>
        </form>
        <a href="tasks.php" class="back">Вернуться назад</a>
    </div>

    <script src="scripts/ajax_get_students.js"></script>
    <script>
        getStudents();
    </script>
</body>
</html>