<?php
    session_start();
    require_once 'database/database.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $sql = "SELECT * FROM attendance WHERE user_id = " . $_SESSION['user_id'];
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Журнал посещений</title>
    <link rel="stylesheet" href="styles/navigation_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/grades_style.css">
    <link rel="stylesheet" href="styles/journal_style.css">
</head>
<body>
    <script src="scripts/journal.js"></script>
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
        <h2>Отметка посещаемости</h2>
        <?php if ($result->num_rows > 0): ?>
            <div class="table_block" id="table_block">
                <div class="static_block">
                    <table id="static_table">
                        <thead>
                            <th>Имя</th>
                        </thead>
                        <tbody>
                            <?php
                                $sql_students = "SELECT * FROM students WHERE user_id = " . $_SESSION['user_id'];
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
                    <table class="scroll_table">
                        <thead>
                            <?php
                                while ($row_att = $result->fetch_assoc()) {
                                    $date = date("d.m", strtotime($row_att['date']));
                                    echo "<th>$date</th>";
                                }
                            ?>
                        </thead>
                        <tbody>
                            <?php
                                $sql_students = "SELECT * FROM students WHERE user_id = " . $_SESSION['user_id'];
                                $res_students = $conn->query($sql_students);
                                if ($result->num_rows > 0 && $res_students->num_rows > 0) {
                                    while ($row_st = $res_students->fetch_assoc()) {
                                        echo "<tr>";
                                        $datesArr = explode(", ", $row_st["dates"]);
                                        for ($col = 0; $col < count($datesArr); $col++) {
                                            if ($datesArr[$col] == '-') {
                                                echo "<td><a href='#' class='mark_link' data_student_id='" . $row_st["id"] . "' mark_index='" . $col . "'>+</a></td>";
                                            } else {
                                                echo "<td><a href='#' class='mark_link' data_student_id='" . $row_st["id"] . "' mark_index='" . $col . "'>Н</a></td>";
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
            <form action="inserts/insert_dates.php" method="POST" class="form_add_date">
                <label for="days_per_week">Количество дней в неделю:</label>
                <input type="number" name="days_per_week" id="days_per_week" min="1" max="7" onchange="updateCheckboxes()" required>

                <br>

                <div id="checkbox_container"></div>

                <label for="start_date">Дата начала обучения</label>
                <input type="date" name="start_date" id="start_date" required>

                <label for="start_date">Дата окончания обучения</label>
                <input type="date" name="end_date" id="end_date" required>

                <input type="submit" value="Готово" id="add_list">
            </form>
        <?php endif; ?>
    </div>

    <script src="scripts/journal_edit.js" defer></script>
</body>
</html>