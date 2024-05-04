<?php
    session_start();
    require_once 'database/database.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $sql = "SELECT group_name FROM groups WHERE group_name = '-' AND user_id = " . $_SESSION['user_id'];
    $res = $conn->query($sql);

    if ($res->num_rows < 1) {
        $sql = "INSERT INTO groups (group_name, students_count, priority, skills, NOTES, user_id) VALUES ('-', 0, '-', '-', '-', '" . $_SESSION['user_id'] . "')";
        $conn->query($sql);
    }

    $sql = "SELECT group_name FROM groups WHERE user_id = " . $_SESSION['user_id'];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $group_id = $row["group_name"];
            $update_sql = "UPDATE groups SET students_count = (SELECT COUNT(*) FROM students WHERE group_num = '$group_id') WHERE group_name = '$group_id' AND user_id = " . $_SESSION['user_id'];

            $conn->query($update_sql);
        }
    }

    function topThree($arr) {
        $counts = array_count_values($arr);
        arsort($counts);
        $top3 = array_slice(array_keys($counts), 0, 3);

        return $top3;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Группы</title>
    <link rel="stylesheet" href="styles/navigation_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/groups_style.css">
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
        <h2>Список групп</h2>
        <div class="table-block" id="table-block">
            <table id="students-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название группы</th>
                        <th>Количество учеников</th>
                        <th>Приоритет группы</th>
                        <th>Навыки</th>
                        <th>Заметки</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT id, group_name, students_count, priority, skills, NOTES FROM groups WHERE user_id = " . $_SESSION['user_id'];
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                if ($row["group_name"] != "-") {
                                    $skill = explode(", ", $row["skills"]);
                                    $skill = topThree($skill);
                                    $skill = implode(", ", $skill);
                                    echo "<tr><td>".$row["id"]."</td><td><a href='#' title='Нажмите, чтобы изменить название группы' class='group_link' group_id='" . $row["id"] . "'>" . $row["group_name"]."</a></td><td>".$row["students_count"]."</td><td><a href='#' class='priority_link' group_id='" . $row["id"] . "'>" . $row["priority"] . "</a></td><td>" .$skill."</td><td><a href='#' class='group_notes_link' group_id='" . $row["id"] . "'>" . $row["NOTES"] . "</a></td></tr>";
                                } else {
                                    $skill = explode(", ", $row["skills"]);
                                    $skill = topThree($skill);
                                    $skill = implode(", ", $skill);
                                    echo "<tr><td>".$row["id"]."</td><td title='Это название нельзя изменить'>"."Без группы"."</td><td>".$row["students_count"]."</td><td>".$row["priority"]."</td><td>" . $skill . "</td><td>".$row["NOTES"]."</td></tr>";
                                }
                            }
                        } else {
                            echo "<h3 style='text-align: center' id='zeroResult'>Групп пока нет</h3>";
                        }
                    ?>
                </tbody>
            </table>
            <div id="pagination"></div>
        </div>

        <div id="group_modal">
            <div class="group_modal_content">
                <span class="close_group_modal">&times;</span>
                <h3>Введите новое название группы</h3>
                <input type="text" id="group_input">
                <button id="submit_group_name">Применить</button>
            </div>
        </div>

        <div id="priority_modal">
            <div class="priority_modal_content">
                <span class="close_priority_modal">&times;</span>
                <h3>Введите новый приоритет группы</h3>
                <input type="text" id="priority_input">
                <button id="submit_priority">Применить</button>
            </div>
        </div>

        <div id="notes_modal">
            <div class="notes_modal_content">
                <span class="close_notes_modal">&times;</span>
                <h3>Введите заметку</h3>
                <input type="text" id="notes_input">
                <button id="submit_notes">Применить</button>
            </div>
        </div>

        <button class="add-button">Добавить</button>
        <div class="form-block">
            <form action="inserts/insert_group.php" method="POST">
                <div class="form-container">
                    <label for="group-name">Название группы</label>
                    <input type="text" name="group-name" required>
                </div>
                <div class="form-container">
                    <label for="group-priority">Приоритет группы</label>
                    <input type="text" name="group-priority" required>
                </div>
                <div class="form-container">
                    <label for="group-skills">Навыки</label>
                    <input type="text" name="group-skills">
                </div>
                <div class="form-container">
                    <label for="group-notes">Заметки</label>
                    <input type="text" name="group-notes">
                </div>
                <div class="form-container">
                    <input type="submit" name="add-group" value="Отправить">
                </div>
            </form>
        </div>
    </div>

    <script src="scripts/groups_filter.js" defer></script>
    <script src="scripts/form.js" defer></script>
    <script src="scripts/group_name_edit.js" defer></script>
</body>
</html>