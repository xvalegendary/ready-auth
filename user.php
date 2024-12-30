<?php
session_start();
include "conf.php"; 


if (isset($_COOKIE['ses_code'])) {
    $ses_code = $_COOKIE['ses_code'];

    
    $stmt = $conn->prepare("
        SELECT U.id, U.login, U.mail, UD.last_name, UD.sur_name, UD.year_birth, UD.gender 
        FROM USER U 
        JOIN user_data UD ON U.id = UD.Id_user 
        WHERE U.ses_code = ?
    ");
    $stmt->bind_param("s", $ses_code);
    $stmt->execute();
    $result = $stmt->get_result();

  
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

       
        echo "<h1>Добро пожаловать, " . htmlspecialchars($user['login']) . "!</h1>";
        echo "<p>Фамилия: " . htmlspecialchars($user['last_name']) . "</p>";
        echo "<p>Имя: " . htmlspecialchars($user['sur_name']) . "</p>";
        echo "<p>Email: " . htmlspecialchars($user['mail']) . "</p>";
        echo "<p>Год рождения: " . htmlspecialchars($user['year_birth']) . "</p>";
        echo "<p>Пол: " . htmlspecialchars($user['gender']) . "</p>";
    } else {
        header('Location index.php');
    }
} else {
    header('Location index.php');
}
?>