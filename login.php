<?php
    session_start();
    require_once 'database/database.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);

        $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Неверное имя пользователя или пароль.";
            }
        } else {
            $error = "Неверное имя пользователя или пароль.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="styles/auth_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Авторизация</h2>
    <form method="post" class="auth_form">
        <input type="text" name="username" placeholder="Логин или email">
        <input type="password" name="password" placeholder="Пароль">
        <input type="submit" value="Войти">
    </form>
    <a href="forgot_password.php">Забыли пароль?</a>
    <a href="registration.php">Нет аккаунта?</a>
</body>
</html>