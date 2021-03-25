<?php
// Файлы phpmailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// Переменные, которые отправляет пользователь

if (isset($_POST['name']))
    $name = $_POST['name'];
else
    $name = '';
if (isset($_POST['email']))
    $email = $_POST['email'];
else
    $email = '';
if (isset($_POST['message']))
    $message = $_POST['message'];
else
    $message = 'Lorem ipsum dolor sit amet.';
if (isset($_POST['file'])) {
    $file = $_POST['file'];
    $rfile = "Файл прикреплён.";
} else
    $rfile = "Файл не прикреплён.";

if ((!isset($_POST['name'])) && (!isset($_POST['email'])) && (!isset($_POST['message']))) 
	die("Входные данные пусты. Скрипт завершается.");

// Формирование самого письма
$title = "Новое обращение Best Tour Plan";
$body = "
<h2>Новое обращение</h2>
<b>Имя:</b> $name<br>
<b>Телефон:</b> $phone<br><br>
<b>Почта:</b> $email<br><br>
<b>Сообщение:</b><br>$message";

// Настройки PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->isSMTP();
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    // $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    // Настройки вашей почты
    $mail->Host       = 'smtp.gmail.com'; // SMTP сервера вашей почты
    $mail->Username   = 'specialformailtest@gmail.com'; // Логин на почте
    $mail->Password   = 'Test1488'; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->setFrom('specialformailtest@gmail.com', 'John John'); // Адрес самой почты и имя отправителя

    // Получатель письма
    $mail->addAddress('blackwoodey@mail.ru');

    // Прикрипление файлов к письму
    if (!empty($file['name'][0])) {
        for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
            $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
            $filename = $file['name'][$ct];
            if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
                $mail->addAttachment($uploadfile, $filename);
                $rfile = "Файл $filename прикреплён.";
            } else {
                $rfile = "Не удалось прикрепить файл $filename";
            }
        }
    }
// Отправка сообщения
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;

// Проверяем отравленность сообщения
    if ($mail->send()) {
        $result = "success";
        $status = "Сообщение успешно отправлено.";
    } else {
        $result = "error";
        $status = "Сообщение не было отправлено.";
    }

} catch (Exception $e) {
    $result = "error";
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}

// Отображение результата
header('Location: thankyou.html');