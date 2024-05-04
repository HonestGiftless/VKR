<?php
    session_start();
    require_once 'database/database.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    function most_freq($arr) {
        $counts = array_count_values($arr);
        arsort($counts);

        $resultArr = array_slice($counts, 0, 3);
        $resultArrKeys = array_filter(array_keys($resultArr), function($val) {
            return $val !== '' && $val !== ' ';
        });

        $result = implode(", ", $resultArrKeys);
        
        return $result;
    }

    function three_latest($arr) {
        $result_arr = array();
        if (count($arr) >= 3) { # 6 => 5; 4; 3; 2;
            for ($i = count($arr) - 1; $i > count($arr) - 3; $i--) {
                $result_arr[] = $arr[$i];
            }
        } else {
            $result_arr = $arr;
        }

        return implode(", ", $result_arr);
    }

    $studentsQuery = "SELECT * FROM students WHERE user_id = " . $_SESSION["user_id"];
    $groupsQuery = "SELECT * FROM groups WHERE user_id = " . $_SESSION["user_id"];
    $tasksQuery = "SELECT * FROM tasks WHERE user_id = " . $_SESSION["user_id"];
    $lessonsQuery = "SELECT  * FROM attendance WHERE user_id = " . $_SESSION["user_id"];
    $rolesQuery = "SELECT * FROM roles";

    $studentsResult = $conn->query($studentsQuery); // Студенты
    $groupsResult = $conn->query($groupsQuery); // Группы
    $tasksResult = $conn->query($tasksQuery); // Задания
    $lessonsResult = $conn->query($lessonsQuery); // Даты занятий
    $rolesResult = $conn->query($rolesQuery); // Роли
    $roles = $rolesResult->fetch_assoc();

    $taskInGroupCount = 0; // Количество групповых заданий
    $taskNotInGroupCount = 0; // Количество индивидуальных заданий
    $gradesCount = 0; // Количество выставленных оценок
    $grades = ''; // Строка с оценками
    $studentsInGroup = 0; // Количество учеников в группах
    $studentsNotInGroup = 0; // Количество учеников без групп
    $pass = '';
    $passCount = 0;

    if ($tasksResult->num_rows > 0) {
        while ($taskRow = $tasksResult->fetch_assoc()) {
            if ($taskRow["has_group"] == '0') {
                $taskNotInGroupCount += 1;
            } else {
                $taskInGroupCount += 1;
            }
        }
    }

    if ($studentsResult->num_rows > 0) {
        while ($studentRow = $studentsResult->fetch_assoc()) {
            $grades .= $studentRow["grades"] . ", ";
            if ($studentRow["group_num"] != "-") {
                $studentsInGroup += 1;
            } else {
                $studentsNotInGroup += 1;
            }
            $pass .= $studentRow["dates"] . ", ";
        }
    }
    $grades = explode(",", $grades);
    $pass = explode(", ", $pass);

    foreach($grades as $value) {
        if (is_numeric($value)) {
            $gradesCount += 1;
        }
    }

    foreach($pass as $value) {
        if (stristr($value, "Н")) {
            $passCount += 1;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика</title>
    <link rel="stylesheet" href="styles/navigation_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/statistic_style.css">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
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
        <h2>Статистика</h2>
        <div class="general_stat_container">
            <h3>Общая статистика</h3>
            <div class="students_count_container">
                <table class="general_static_table">
                    <thead>
                        <tr>
                            <th>Общее количество учеников</th>
                            <th>Количество учеников в группах</th>
                            <th>Количество учеников без групп</th>
                            <th>Общее количество групп</th>
                            <th>Общее количество заданий</th>
                            <th>Количество групповых заданий</th>
                            <th>Количество индивидуальных заданий</th>
                            <th>Общее количество выставленных оценок</th>
                            <th>Общее количество занятий</th>
                            <th>Общее количество пропущенных детьми занятий</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><a href="index.php" class="link_to_page"><?=$studentsResult->num_rows?></a></th>
                            <th><a href="index.php" class="link_to_page"><?=$studentsInGroup?></a></th>
                            <th><a href="index.php" class="link_to_page"><?=$studentsNotInGroup?></a></th>
                            <th><a href="groups.php" class="link_to_page"><?=$groupsResult->num_rows?></a></th>
                            <th><a href="tasks.php" class="link_to_page"><?=$tasksResult->num_rows?></a></th>
                            <th><a href="tasks.php" class="link_to_page"><?=$taskInGroupCount?></a></th>
                            <th><a href="tasks.php" class="link_to_page"><?=$taskNotInGroupCount?></a></th>
                            <th><a href="grades.php" class="link_to_page"><?=$gradesCount?></a></th>
                            <th><a href="journal.php" class="link_to_page"><?=$lessonsResult->num_rows?></a></th>
                            <th><a href="journal.php" class="link_to_page"><?=$passCount?></a></th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button id="loadChartButton">Загрузить график</button>
            <button id="hideChartButton" style="display: none;">Скрыть график</button>
            <div id="chartContainer" style="display: none;"></div>
        </div>
        <div class="academic_stat_container">
            <h3>Академическая статистика</h3>
            <div class="academic_container">
                <table class="acadcemic_table">
                    <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Общее количество оценок</th>
                            <th>Количество оценок 5</th>
                            <th>Количество оценок 4</th>
                            <th>Количество оценок 3</th>
                            <th>Количество оценок 2</th>
                            <th>Количество оценок 1</th>
                            <th>Количество выполненных заданий</th>
                            <th>Название выполненных заданий</th>
                            <th>Количество пропусков</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $st = "SELECT * FROM students WHERE user_id = " . $_SESSION["user_id"];
                            $resST = $conn->query($st);
                            if ($resST->num_rows > 0) {
                                while ($sr = $resST->fetch_assoc()) {
                                    $counter = 0;
                                    $counter5 = 0;
                                    $counter4 = 0;
                                    $counter3 = 0;
                                    $counter2 = 0;
                                    $counter1 = 0;
                                    $passCounter = 0;

                                    echo "<tr>" . "<td class='name_academic_col'>" . $sr["name"] . "</td>";
                                    $arrGrades = explode(", ", $sr["grades"]);
                                    foreach($arrGrades as $v) {
                                        if (is_numeric($v)) {
                                            $counter += 1;
                                            if ($v == '5') {
                                                $counter5 += 1;
                                            } else if ($v == '4') {
                                                $counter4 += 1;
                                            } else if ($v == '3') {
                                                $counter3 += 1;
                                            } else if ($v == '2') {
                                                $counter2 += 1;
                                            } else if ($v == '1') {
                                                $counter1 += 1;
                                            }
                                        }
                                    }
                                    echo "<td class='name_academic_col'><a href='grades.php' class='counter_link'>$counter</a></td>";
                                    echo "<td class='name_academic_col'><a href='grades.php' class='counter_link'>$counter5</a></td>";
                                    echo "<td class='name_academic_col'><a href='grades.php' class='counter_link'>$counter4</a></td>";
                                    echo "<td class='name_academic_col'><a href='grades.php' class='counter_link'>$counter3</a></td>";
                                    echo "<td class='name_academic_col'><a href='grades.php' class='counter_link'>$counter2</a></td>";
                                    echo "<td class='name_academic_col'><a href='grades.php' class='counter_link'>$counter1</a></td>";

                                    $tt = "SELECT * FROM tasks WHERE user_id = " . $_SESSION["user_id"];
                                    $resTT = $conn->query($tt);

                                    if ($resTT->num_rows > 0) {
                                        $counter = 0;
                                        $nameOfTask = '';
                                        while ($rt = $resTT->fetch_assoc()) {
                                            $arrTask = explode(", ", $rt["done_count_id"]);
                                            if (in_array($sr["id"], $arrTask)) {
                                                $counter += 1;
                                                $nameOfTask .= $rt["name"] .= ", ";
                                                continue;
                                            }
                                        }
                                        $nameOfTask = explode(", ", $nameOfTask);
                                        $resulstNamesTask = array();
                                        foreach($nameOfTask as $v) {
                                            if ($v != '') {
                                                $resulstNamesTask[] = $v;
                                            }
                                        }
                                        $nameOfTask = implode(", ", $resulstNamesTask);
                                        echo "<td class='name_academic_col'><a href='tasks.php' class='counter_link'>$counter</a></td>";
                                        echo "<td class='name_academic_col'><a href='tasks.php' class='task_link'>$nameOfTask</a></td>";
                                    }

                                    $passArr = explode(", ", $sr["dates"]);
                                    foreach($passArr as $pass) {
                                        if ($pass == 'Н') {
                                            $passCounter += 1;
                                        }
                                    }

                                    echo "<td class='name_academic_col'><a href='journal.php' class='counter_link'>$passCounter</a></td>";

                                    echo "</tr>";
                                }
                            } else {
                                echo "<h4>Пока невозможно создать данную статистику</h4>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <button id="loadAcademStat">Загрузить академическую статистику</button>
            <button id="hideAcademStat" style="display: none;">Скрыть график</button>
            <div id="academChartContainer" style="display: none;"></div>
        </div>
        <div class="personal_stat_container">
            <h3>Индивидуальная статистика</h3>
            <div class="personal_container">
                <table class="personal_table">
                    <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Наиболее частые навыки</th>
                            <th>Последние заметки</th>
                            <th>Все навыки ученика</th>
                            <th>Все заметки ученика</th>
                            <th title='Определяются исходя из отмеченных навыков, при отметке задания'>Предполагаемая роль в команде</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sqlName = "SELECT * FROM students WHERE user_id = " . $_SESSION["user_id"];
                            $resultName = $conn->query($sqlName);
                            if ($resultName->num_rows > 0) {
                                while ($rowName = $resultName->fetch_assoc()) {
                                    $rolesCounter = array(
                                        "Лидер" => 0,
                                        "Мотиватор" => 0,
                                        "Исполнитель" => 0,
                                        "Координатор" => 0
                                    );

                                    $rolesLider = explode(", ", $roles["lider"]);
                                    $rolesLider = array_map("trim", $rolesLider);
                                    $rolesLider = array_diff($rolesLider, array(''));

                                    $rolesMotivator = explode(", ", $roles["motivator"]);
                                    $rolesMotivator = array_map("trim", $rolesMotivator);
                                    $rolesMotivator = array_diff($rolesMotivator, array(''));

                                    $rolesExecutor = explode(", ", $roles["executor"]);
                                    $rolesExecutor = array_map("trim", $rolesExecutor);
                                    $rolesExecutor = array_diff($rolesExecutor, array(''));

                                    $rolesCoordinator = explode(", ", $roles["coordinator"]);
                                    $rolesCoordinator = array_map("trim", $rolesCoordinator);
                                    $rolesCoordinator = array_diff($rolesCoordinator, array(''));

                                    echo "<tr>";
                                    echo "<td class='name_academic_col'>" . $rowName["name"] . "</td>";
                                    $skill = most_freq(explode(", ", $rowName["skills"]));
                                    echo "<td class='name_academic_col'>" . $skill . "</td>";
                                    $latestNotes = three_latest(explode(", ", $rowName["notes"]));
                                    if ($latestNotes != "") {
                                        echo "<td class='name_academic_col'>" . $latestNotes . "</td>";
                                    } else {
                                        echo "<td class='name_academic_col'>Заметок для этого ученика пока нет</td>";
                                    }
                                    echo "<td class='link_col'><a href='#' class='download_link' onclick='downloadSkillsFile(" . $rowName["id"] . ")'>Скачать</a></td>";
                                    echo "<td class='link_col'><a href='#' class='download_link' onclick='downloadNotesFile(" . $rowName["id"] . ")'>Скачать</a></td>";
                                    $arr = explode(", ", $rowName["skills"]);
                                    $lowercaseArr = array();
                                    
                                    foreach ($arr as $word) {
                                        $lowercaseWord = mb_strtolower($word, 'UTF-8'); // Используем mb_strtolower для работы с UTF-8
                                        $lowercaseArr[] = $lowercaseWord;
                                    }

                                    

                                    foreach($lowercaseArr as $value) {
                                        if (in_array($value, $rolesLider)) {
                                            $rolesCounter["Лидер"] += 1;
                                        }
                                        if (in_array($value, $rolesMotivator)) {
                                            $rolesCounter["Мотиватор"] += 1;
                                        }
                                        if (in_array($value, $rolesExecutor)) {
                                            $rolesCounter["Исполнитель"] += 1;
                                        }
                                        if (in_array($value, $rolesCoordinator)) {
                                            $rolesCounter["Координатор"] += 1;
                                        }
                                    }                      

                                    $maxValue = max($rolesCounter);
                                    $maxKeys = array_keys($rolesCounter, $maxValue);
                                    $maxKey = reset($maxKeys);

                                    if ($rolesCounter["Лидер"] != 0 || $rolesCounter["Мотиватор"] || 0 || $rolesCounter["Исполнитель"] != 0 || $rolesCounter["Координатор"] != 0) {
                                        echo "<td class='name_academic_col'>" . $maxKey . "</td>";
                                    } else {
                                        echo "<td class='name_academic_col'>Пока навыков для определения роли недостаточно</td>";
                                    }

                                    echo "</tr>";
                                }
                            } else {
                                echo "<h4>Пока невозможно создать данную статистику</h4>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <button id="loadPersonalStat">Загрузить персональную статистику статистику</button>
            <button id="hidePersonalStat" style="display: none;">Скрыть графики</button>
            <div id="personalChartContainer" style="display: none;"></div>
        </div>
    </div>
    <script src="scripts/download_skills.js" defer></script>
    <script src="generation/generation_students.js" defer></script>
    <script src="generation/generation_academic.js" defer></script>
    <script src="generation/generation_person.js" defer></script>
</body>
</html>