<?php

require_once 'database/database.php';

$sql = "SELECT * FROM tasks";
$result_sql = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оценки</title>
    <link rel="stylesheet" href="styles/navigation_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/grades_style.css">
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
        <h2>Оценки обучающихся</h2>
        <?php if ($result_sql->num_rows > 0): ?>
            <div class="table_block" id="table_block">
                <div class="static_block">
                    <table id="static_table">
                        <thead>
                            <th>Имя</th>
                        </thead>
                        <tbody>
                            <?php
                                $sql_students = "SELECT * FROM students";
                                $res_students = $conn->query($sql_students);
                                if ($res_students->num_rows > 0) {
                                    while ($row_students = $res_students->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td class='sticky'>" . $row_students["name"] . "</td>";
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="scroll_block">
                    <table class="scroll_table" id="scroll_table">
                        <thead>
                            <?php
                                $grade_count = $result_sql->num_rows;
                                if ($grade_count > 0) {
                                    for ($i = 0; $i < $grade_count; $i++) {
                                        echo "<th>Оценка № " . $i + 1 . "</th>";
                                    }
                                } else {
                                    echo "<h3 style='text-align: center' id='zeroResult'>Заданий еще нет. Проставить оценки нельзя.</h3>";
                                }
                            ?>
                        </thead>
                        <tbody>
                            <?php
                                $sql_s = "SELECT * FROM students";
                                $res_s = $conn->query($sql_s);
                                if ($res_s->num_rows > 0) {
                                    while ($row_s = $res_s->fetch_assoc()) {
                                        echo "<tr>";
                                        if ($row_s["grades"] == '-') {
                                            for ($col = 0; $col < $result_sql->num_rows; $col++) {
                                                echo "<td><a href='#' class='grade_button' data_student_id='" . $row_s["id"] . "' data_grade_index='" . $col . "'>+</a></td>";
                                            }
                                        } else {
                                            $grades_arr = explode(",", $row_s["grades"]);
                                            $grades_arr = array_map("trim", $grades_arr);
                                            for ($col = 0; $col < count($grades_arr); $col++) {
                                                if ($grades_arr[$col] == '-') {
                                                    echo "<td><a href='#' class='grade_button' data_student_id='" . $row_s["id"] . "' data_grade_index='" . $col . "'>+</a></td>";
                                                } else {
                                                    echo "<td><a href='#' class='grade_button' data_student_id='" . $row_s["id"] . "' data_grade_index='" . $col . "'>" . $grades_arr[$col] . "</a></td>";
                                                }
                                            }
                                        }
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <h3>Нужно добавить задания, чтобы выставлять оценки</h3>
        <?php endif; ?>
        <div id="grade_modal" class="modal">
            <div class="modal_content">
                <span class="close">&times;</span>
                <h3>Выберите оценку</h3>
                <input type="number" id="grade_input" min="1" max="5">
                <button id="submit_grade">Отправить</button>
            </div>
        </div>
    </div>

    <script src="scripts/grades.js" defer></script>
    <script src="scripts/grades_color.js" defer></script>
</body>
</html>