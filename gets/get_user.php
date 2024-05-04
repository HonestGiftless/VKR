<?php

require_once '../database/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';


$login = $_POST['username'];

function sendMail($mail) {
    $code = mt_rand(100000, 999999);

    $email = new PHPMailer(true);

    try {
        $email->isSMTP();

        $email->Host = 'smtp.mail.ru';
        $email->SMTPAuth = true;

        $email->Username = 'spodgotovki@bk.ru';
        $email->Password = 'zx23142003';

        $email->SMTPSecure = 'tls';
        $email->Port = 465;

        $email->setFrom('spodgotovki@bk.ru', 'Сервис для подготовки команд школьников');
        $email->addAddress($mail);

        $email->isHTML(true);
        $email->Subject = 'Ваш проверочный код';
        $email->Body = 'Ваш проверочный код: ' . $code;

        $email->send();
        echo 'Код успешно отправлен!';
    } catch (Exception $e) {
        echo "Ошибка при отправке кода: {$email->ErrorInfo}";
    }
}

if (strpos($login, '@') !== false) {
    $sql = "SELECT * FROM users WHERE email = '$login'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        sendMail($login);
    }
} else {
    $sql = "SELECT * FROM users WHERE username = '$login'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $email = $user["email"];
        sendMail($email);
    }
}