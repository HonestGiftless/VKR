<?php

session_start();
require_once 'database/database.php';

date_default_timezone_set('Asia/Irkutsk');
$current_date_obj = new DateTime();
$current_date = $current_date_obj->format('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmedPassword = $_POST['confirm_password'];
    $email = $_POST['email'];

    if (isset($_POST['has_mail'])) {
        $mailing = 1;
    } else {
        $mailing = 0;
    }

    if ($password != $confirmedPassword) {
        echo "<p class='error_password'>Пароли не совпадают</p>";
    } else {
        $sql_check = "SELECT * FROM users";
        $result_check = $conn->query($sql_check);

        $has_acc = false;
        $has_mail = false;

        if ($result_check->num_rows > 0) {
            while ($row = $result_check->fetch_assoc()) {
                if ($row["username"] == $username) {
                    $has_acc = true;
                    break;
                }
                if ($row["email"] == $email) {
                    $has_mail = true;
                    break;
                }
            }
        }

        if ($has_acc && !$has_mail) {
            echo "<p style='font-family: Inter; color: white'>" . "Такой логин уже существует! Попробуйте <a href='login.php'>авторизоваться</a>" . "</p>";
        } else if ($has_mail && !$has_acc) {
            echo "<p style='font-family: Inter; color: white'>" . "Такая почта уже зарегистрирована! Попробуйте <a href='login.php'>авторизоваться</a>" . "</p>";
        } else if ($has_mail && $has_acc) {
            echo "Логин и почта уже есть в системе. Попробуйте <a href='login.php'>авторизоваться</a>";
        } else if (!$has_acc && !$has_mail) {
            $username = htmlspecialchars($username);
            $password = password_hash(htmlspecialchars($password), PASSWORD_DEFAULT);
            $email = htmlspecialchars($email);
    
            if ($mailing) {
                $sql = "INSERT INTO users (username, password, email, mailing, reg_date) VALUES ('$username', '$password', '$email', 1, '$current_date')";
            } else {
                $sql = "INSERT INTO users (username, password, email, mailing, reg_date) VALUES ('$username', '$password', '$email', 0, '$current_date')";
            }
            $result = $conn->query($sql);
            header("Location: login.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles/register_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Регистрация</h2>
    <form method="POST" class="reg_form">
        <input type="text" name="username" placeholder="Имя пользователя" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <input type="password" name="confirm_password" placeholder="Повторите пароль" required>
        <input type="email" name="email" placeholder="Электронная почта" required>
        <label for="mailing">Я согласен получать информационную рассылку</label>
        <div class="checkbox-wrapper">
            <input type="checkbox" id="check" name="has_mail"/>
            <label for="check" style="--size: 20px">
                <svg viewBox="0,0,50,50">
                    <path d="M5 30 L 20 45 L 45 5"></path>
                </svg>
            </label>
        </div>
        <input type="submit" value="Зарегистрироваться">
    </form>
    <a href="login.php">Есть аккаунт?</a>
</body>
</html>