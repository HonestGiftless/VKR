<?php
    session_start();
    require_once 'database/database.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $userId = $_SESSION['user_id'];


    function getDaySuffix($number) {
        if ($number % 10 == 1 && $number != 11) {
            return 'день';
        } elseif ($number % 10 >= 2 && $number % 10 <= 4 && ($number < 10 || $number > 20)) {
            return 'дня';
        } else {
            return 'дней';
        }
    }
    
    $sql = "SELECT * FROM users WHERE id = $userId";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();

    date_default_timezone_set('Asia/Irkutsk');
    $current_time = date("H");

    $current_date_obj = new DateTime();
    $current_date = $current_date_obj->format('Y-m-d');

    $reg_date = new DateTime($data["reg_date"]);
    $interval = $reg_date->diff($current_date_obj);
    $days_diff = $interval->days;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки</title>
    <link rel="stylesheet" href="styles/navigation_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
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
        <h2>Настройки пользователя</h2>
        <?php
            if ($current_time >= 5 && $current_time < 12) {
                echo "<h2>";
                echo "Доброе утро, " . $data["username"] . "!";
                echo "</h2>";
            } else if ($current_time >= 12 && $current_time < 18) {
                echo "<h2>";
                echo "Добрый день, " . $data["username"] . "!";
                echo "</h2>";
            } else if ($current_time >= 18 && $current_time < 23) {
                echo "<h2>";
                echo "Добрый вечер, " . $data["username"] . "!";
                echo "</h2>";
            } else {
                echo "<h2>";
                echo "Доброй ночи, " . $data["username"] . "!";
                echo "</h2>";
            }

            if ($days_diff > 5) {
                echo "<p>Спасибо, что пользуетесь нашим сервисом уже " . $days_diff . " " . getDaySuffix($days_diff) . ":)</p>";
            }
        ?>
    </div>
</body>
</html>