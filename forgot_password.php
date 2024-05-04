<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/auth_style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Восстановление пароля</h2>

    <form method="post" action="gets/get_user.php" class='recovery_form'>
        <label for="username">Введите логин или адрес электронной почты</label>
        <input type="text" name="username" placeholder="mail@domen.ru" required>
        <input type="submit" value="Восстановить">
    </form>
    
    <a href="login.php">Перейти к авторизации</a>

    <script src="gets/send_mail.js" defer></script>
</body>
</html>