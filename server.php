<?php session_start();
include "conf.php";
require 'vendor/autoload.php'; // composer автолоудит phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'register') {
        // Регистрация пользователя
        $login = $_POST['login'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // bcrypt
        $last_name = $_POST['last_name'];
        $sur_name = $_POST['sur_name'];
        $email = $_POST['email'];
        $year_birth = $_POST['year_birth'];
        $gender = $_POST['gender'];

        $stmt = $conn->prepare("SELECT * FROM USER WHERE Login = ? OR Mail = ?");
        $stmt->bind_param("ss", $login, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO USER (Login, password, Mail) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $login, $hashed_password, $email);
            $stmt->execute();

            $user_id = $conn->insert_id;
            $stmt = $conn->prepare("INSERT INTO USER_DATA (Id_user, Last_name, Sur_name, year_birth, gender) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issis", $user_id, $last_name, $sur_name, $year_birth, $gender);
            $stmt->execute();

            echo json_encode(['status' => 'success', 'message' => '[+] success reg']);
        } else {
            echo json_encode(['status' => 'error', 'message' => '[-] fail reg']);
        }
    }

    if ($action === 'login') {

        $login = $_POST['login'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM USER WHERE Login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $ses_code = bin2hex(random_bytes(6));
                $_SESSION['ses_code'] = $ses_code;

                $stmt = $conn->prepare("UPDATE USER SET Ses_code = ? WHERE Login = ?");
                $stmt->bind_param("ss", $ses_code, $login);
                $stmt->execute();

                setcookie('ses_code', $ses_code, time() + (86400 * 30), "/");

                echo json_encode(['status' => 'success', 'message' => 'success auth']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'invalid log or pass']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'invalid log or pass']);
        }
    }

    if ($action === 'reset') {
        $email = $_POST['email'];

        $stmt = $conn->prepare("SELECT * FROM USER WHERE mail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $mail_code = bin2hex(random_bytes(3));

            $stmt = $conn->prepare("UPDATE USER SET mail_code = ? WHERE mail = ?");
            $stmt->bind_param("ss", $mail_code, $email);
            $stmt->execute();

            // настройка phpmailer

            $mail = new PHPMailer(true);
            try {

                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->Host = ''; // smtp host
                $mail->SMTPAuth = true;
                $mail->Username = ''; // mail 
                $mail->Password = ''; // pass 
                $mail->SMTPSecure = "ssl";
                $mail->Port = 465; // port
                $mail->CharSet = 'UTF-8';


                $mail->setFrom('', ''); // mail // name
                $mail->addAddress($email);


                $mail->isHTML(true);
                $mail->Subject = "Восстановление пароля";
                $mail->Body    = '<html><body>';
                $mail->Body .= '<h1>Восстановление пароля</h1>';
                $mail->Body .= '<p>Чтобы восстановить пароль, введите данный код на сайте:</p>';
                $mail->Body .= '<p>' . $mail_code . '</p>';
                $mail->Body .= '</body></html>';


                $mail->send();
                echo json_encode(['status' => 'success', 'message' => '[!] check your email']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => '[-] err: ' . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => '[-] email not found']);
        }
    }

    if ($action === 'verify_code') {
        $email = $_POST['email'];
        $input_code = $_POST['code'];
        $new_password = $_POST['new_password'];

        $stmt = $conn->prepare("SELECT * FROM USER WHERE mail = ? AND mail_code = ?");
        $stmt->bind_param("ss", $email, $input_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE USER SET password = ?, mail_code = NULL WHERE mail = ?");
            $stmt->bind_param("ss", $hashed_password, $email);
            $stmt->execute();

            echo json_encode(['status' => 'success', 'message' => '[+] password updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => '[-] invalid code']);
        }
    }
}
