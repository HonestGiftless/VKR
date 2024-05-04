<?php
    session_start();
    require_once 'database/database.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $query = "SELECT * FROM tasks WHERE user_id = " . $_SESSION['user_id'];
    $resQuery = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задания</title>
    <link rel="stylesheet" href="styles/navigation_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/tasks_style.css">
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
        <h2>Список заданий</h2>
        <?php if ($resQuery->num_rows > 0): ?>
            <div class="filters">
                <input type="button" value="Групповые задания" class="filter-btn" id="inGroupBtn">
                <input type="button" value="Индивидуальные задания" class="filter-btn" id="noGroupBtn">
            </div>
            <div class="table-block" id="table-block">
                <table id="students-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Направление</th>
                            <th>Выполнили</th>
                            <th>Дедлайн</th>
                            <th>Сложность</th>
                            <th>Тип задания</th>
                            <th>Проверить/Отметить выполнение</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT * FROM tasks WHERE user_id = " . $_SESSION['user_id'];
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if ($row["has_group"] == "1") {
                                        if ($row["group_done_count_id"] != '0') {
                                            $arr = explode(",", $row["group_done_count_id"]);
                                            $arr = array_map("trim", $arr);
                                            $arr = array_diff($arr, array(''));
                                            $res_group_names = array();

                                            foreach ($arr as $value) {
                                                $sss = "SELECT * FROM groups WHERE id = $value AND user_id = " . $_SESSION['user_id'];
                                                $resss = $conn->query($sss);
                                                $done = $resss->fetch_assoc();
                                                $res_group_names[] = $done["group_name"];
                                            }

                                            $donner = implode(", ", $res_group_names);

                                            echo "<tr><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td><td>" . $row["direct"] . "</td><td>" . $donner . "</td><td>" . $row["deadline"] . "</td><td>" . $row["complex"] . "</td><td>Групповое</td><td><a href='task_mark.php?task_id=" . $row["id"] . "'>Проверить/Отметить</a></td></tr>";
                                        } else {
                                            echo "<tr><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td><td>" . $row["direct"] . "</td><td>0</td><td>" . $row["deadline"] . "</td><td>" . $row["complex"] . "</td><td>Групповое</td><td><a href='task_mark.php?task_id=" . $row["id"] . "'>Проверить/Отметить</a></td></tr>";
                                        }
                                    } else {
                                        if ($row["done_count"] != '0') {
                                            echo "<tr><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td><td>" . $row["direct"] . "</td><td>" . $row["done_count"] . "</td><td>" . $row["deadline"] . "</td><td>" . $row["complex"] . "</td><td>Индивидуальное</td><td><a href='task_mark_none_group.php?task_id=" . $row["id"] . "'>Проверить/Отметить</a></td></tr>";
                                        } else {
                                            echo "<tr><td>" . $row["id"] . "</td><td>" . $row["name"] . "</td><td>" . $row["direct"] . "</td><td></td><td>" . $row["deadline"] . "</td><td>" . $row["complex"] . "</td><td>Индивидуальное</td><td><a href='task_mark_none_group.php?task_id=" . $row["id"] . "'>Проверить/Отметить</a></td></tr>";
                                        }
                                    }
                                }
                            } else {
                                echo "<h3 style='text-align: center' id='zeroResult'>Заданий пока нет</h3>";
                            }
                        ?>
                    </tbody>
                </table>
                <div id="pagination"></div>
            </div>
            <button class="add-button">Добавить задание</button>
            <div class="form-block">
                <form action="inserts/insert_task.php" method="POST">
                    <div class="form-container">
                        <label for="name">Название задания</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-container">
                        <label for="direct">Направление задания</label>
                        <input type="text" name="direct">
                    </div>
                    <div class="form-container">
                        <label for="deadline">Срок окончания задания</label>
                        <input type="date" name="deadline">
                    </div>
                    <div class="form-container">
                        <label for="complex">Сложность задания</label>
                        <input type="text" name="complex">
                    </div>
                    <div class="form-container">
                        <label for="has_group">Групповое задание</label>
                        <input type="checkbox" name="has_group">
                    </div>
                    <div class="form-container">
                        <input type="submit" name="add-task" value="Отправить">
                    </div>
                </form>
            </div>
        <?php else: ?>
            <h2 style="margin: 5vh 0; font-family: Inter;">Заданий пока нет</h2>
            <button class="add-button">Добавить задание</button>
            <div class="form-block">
                <form action="inserts/insert_task.php" method="POST">
                    <div class="form-container">
                        <label for="name">Название задания</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-container">
                        <label for="direct">Направление задания</label>
                        <input type="text" name="direct">
                    </div>
                    <div class="form-container">
                        <label for="deadline">Срок окончания задания</label>
                        <input type="date" name="deadline">
                    </div>
                    <div class="form-container">
                        <label for="complex">Сложность задания</label>
                        <input type="text" name="complex">
                    </div>
                    <div class="form-container">
                        <label for="has_group">Групповое задание</label>
                        <div class="checkbox-wrapper-23">
                            <input type="checkbox" id="check-23" name="has_group"/>
                            <label for="check-23" style="--size: 30px">
                                <svg viewBox="0,0,50,50">
                                    <path d="M5 30 L 20 45 L 45 5"></path>
                                </svg>
                            </label>
                        </div>
                    </div>
                    <div class="form-container">
                        <input type="submit" name="add-task" value="Отправить">
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script src="scripts/task_filter.js" defer></script>
    <script src="scripts/form.js" defer></script>
</body>
</html>