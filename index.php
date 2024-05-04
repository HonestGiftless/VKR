<?php
    session_start();
    require_once 'database/database.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $query = "SELECT * FROM students WHERE user_id = " . $_SESSION["user_id"];
    $resQuery = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Студенты</title>
    <link rel="stylesheet" href="styles/navigation_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/index_style.css">
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
        <h2>Список учеников</h2>
        <?php if ($resQuery->num_rows > 0): ?>
        <div class="filters">
            <input type="button" value="В группах" class="filter-btn" id="inGroupBtn">
            <input type="button" value="Без групп" class="filter-btn" id="noGroupBtn">
        </div>
        <div class="table-block" id="table-block">
            <table id="students-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя и фамилия</th>
                        <th>Группа</th>
                        <th>Возрастная группа</th>
                        <th>Навыки</th>
                        <th>Заметки</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT id, name, group_num, age, skills, notes, group_id FROM students WHERE user_id = " . $_SESSION["user_id"];
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $skillsArr = explode(", ", $row["skills"]);
                                if (count($skillsArr) > 3) {
                                    $skills = '';
                                    $counter = array_count_values($skillsArr);
                                    arsort($counter);
                                    $threeElements = array_slice($counter, 0, 3, true);
                                    foreach ($threeElements as $element => $count) {
                                        if ($element !== array_key_last($threeElements)) {
                                            $skills .= $element;
                                            $skills .= ", ";
                                        } else {
                                            $skills .= $element;
                                        }
                                    }
                                } else {
                                    $skills = $row["skills"];
                                }
    
                                if ($row["group_num"] != "-") {
                                    echo "<tr><td>".$row["id"]."</td><td><a class='name_link' href='#' title='Вы можете изменить имя, достаточно нажать на него' data_student_id='" . $row["id"] . "' >" .$row["name"]."</a></td><td><a href='#' class='group_link' title='Нажмите по группе, чтобы ее изменить' data_student_id='" . $row["id"] . "' current_group_id='" . $row["group_id"] . "'>".$row["group_num"]."</a></td><td>".$row["age"]."</td><td>".$skills."</td><td><a href='#' class='notes_link' data_student_id='" . $row["id"] . "'>".$row["notes"]."</td><td class='delete_cell' style='display: none;'><input type='checkbox' class='delete_check' student_id='" . $row["id"] . "'></td></tr>";
                                } else {
                                    echo "<tr><td>".$row["id"]."</td><td><a class='name_link' href='#' title='Вы можете изменить имя, достаточно нажать на него' data_student_id='" . $row["id"] . "' >" .$row["name"]."</a></td><td><a href='#' class='group_link' title='Нажмите по группе, чтобы ее изменить' data_student_id='" . $row["id"] . "' current_group_id='" . $row["group_id"] . "'>Без группы"."</a></td><td>".$row["age"]."</td><td>".$skills."</td><td><a href='#' class='notes_link' data_student_id='" . $row["id"] . "'>".$row["notes"]."</td><td class='delete_cell' style='display: none;'><input type='checkbox' class='delete_check' student_id='" . $row["id"] . "'></td></tr>";
                                }
                            }
                        } else {
                            echo "<h3 style='text-align: center' id='zeroResult'>Учеников пока нет</h3>";
                        }
                    ?>
                </tbody>
            </table>
            <div id="pagination"></div>
        </div>

        <div id="grade_modal" class="modal">
            <div class="modal_content">
                <span class="close">&times;</span>
                <h3>Изменить имя</h3>
                <input type="text" id="grade_input">
                <button id="submit_grade">Изменить</button>
            </div>
        </div>

        <div id="group_modal">
            <div class="group_modal_content">
                <span class="close_group">&times;</span>
                <h3>Выберите группу</h3>
                <select name="groups" id="take_group">
                    <?php
                        $sqlG = "SELECT * FROM groups WHERE user_id = " . $_SESSION["user_id"];
                        $resultG = $conn->query($sqlG);

                        if ($resultG->num_rows > 0) {
                            while ($rowG = $resultG->fetch_assoc()) {
                                if ($rowG["group_name"] != "-") {
                                    echo "<option value='" . $rowG["id"] . "'>" . $rowG["group_name"] . "</option>";
                                } else {
                                    echo "<option value='" . $rowG["id"] . "'>Без группы</option>";
                                }
                            }
                        }
                    ?>
                </select>
                <button id="submit_group">Изменить</button>
            </div>
        </div>

        <div id="notes_modal">
            <div class="notes_modal_content">
                <span class="close_notes">&times;</span>
                <h3>Введите заметки</h3>
                <input type="text" id="notes_input">
                <button id="submit_note">Применить</button>
            </div>
        </div>
        <button class="add-button">Добавить ученика</button>
        <button class="delete-button">Режим удаления</button>
        <div id="delete_container">
            <button id="deleter" style="display: none;">Удалить</button>
        </div>
        <div class="form-block">
            <form action="inserts/insert_students.php" method="POST">
                <div class="form-container">
                    <label for="name">Имя и фамилия</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-container">
                    <label for="group">Группа</label>
                    <select name="group" id="group-selector" class="decorated" required>
                        <?php
                            $sql = "SELECT * FROM groups WHERE user_id = " . $_SESSION["user_id"];
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    if ($row["group_name"] != "-") {
                                        echo "<option value='" . $row["id"] . "'>" . $row["group_name"] . "</option>";
                                    } else {
                                        echo "<option value='" . $row["id"] . "'>" . "Без группы" . "</option>";
                                    }
                                }
                            } else {
                                echo "0 результатов";
                            }
                        
                            $conn ->close();
                        ?>
                    </select>
                </div>
                <div class="form-container">
                    <label for="age">Возраст</label>
                    <input type="text" name="age" required>
                </div>
                <div class="form-container">
                    <label for="notes">Заметки</label>
                    <input type="text" name="notes">
                </div>
                <div class="form-container">
                    <input type="submit" name="add-student" value="Отправить">
                </div>
            </form>
        </div>
        <?php else: ?>
            <h2 style="margin: 5vh 0; font-family: Inter;">Учеников пока нет</h2>
            <button class="add-button">Добавить ученика</button>
            <div class="form-block">
                <form action="inserts/insert_students.php" method="POST">
                    <div class="form-container">
                        <label for="name">Имя и фамилия</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-container">
                        <label for="group">Группа</label>
                        <select name="group" id="group-selector" class="decorated" required>
                            <?php
                                $sql = "SELECT * FROM groups WHERE user_id = " . $_SESSION["user_id"];
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        if ($row["group_name"] != "-") {
                                            echo "<option value='" . $row["id"] . "'>" . $row["group_name"] . "</option>";
                                        } else {
                                            echo "<option value='" . $row["id"] . "'>Без группы</option>";
                                        }
                                    }
                                } else {
                                    echo "0 результатов";
                                }
                            
                                $conn ->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-container">
                        <label for="age">Возраст</label>
                        <input type="text" name="age" required>
                    </div>
                    <div class="form-container">
                        <label for="notes">Заметки</label>
                        <input type="text" name="notes">
                    </div>
                    <div class="form-container">
                        <input type="submit" name="add-student" value="Отправить">
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
    

    <script src="scripts/filter.js" defer></script>
    <script src="scripts/form.js" defer></script>
    
    <script src="scripts/names_edit.js" defer></script>
    <script src="scripts/group_edit.js" defer></script>
    <script src="scripts/notes_edit.js" defer></script>
    <script src="deletes/delete_student.js" defer></script>
</body>
</html>