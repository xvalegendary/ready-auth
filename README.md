# README
## Описание проекта
Данный проект представляет собой систему авторизации, регистрации и восстановления пароля с использованием библиотеки PHPMailer для отправки писем на электронную почту. Проект написан на PHP и предназначен для упрощения процесса управления пользователями на веб-сайте.

## Функционал
Регистрация пользователя: Пользователи могут создавать учетные записи, заполняя форму регистрации.
Авторизация: Пользователи могут входить в систему, используя свои учетные данные.
Восстановление пароля: Пользователи могут восстановить забытый пароль, получив ссылку для сброса пароля на свою электронную почту.
Установка
Клонирование репозитория

git clone https://github.com/xvalegendary/ready-auth.git
cd ready-auth
## Установка зависимостей

> [!WARNING]
> Убедитесь, что у вас установлен Composer. Запустите run.bat

Создание базы данных (phpmyadmin)

Создайте базу данных, указанную в конфигурации, и выполните миграции (если применимо).
```
CREATE TABLE user_data (
    Id_data_user INT PRIMARY KEY AUTO_INCREMENT,
    Id_user INT NOT NULL,
    last_name CHAR(24) NOT NULL,
    sur_name CHAR(24) NOT NULL,
    year_birth INT NOT NULL,
    gender CHAR(1) NOT NULL
);

CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    login CHAR(12) NOT NULL,
    password CHAR(12) NOT NULL,
    mail CHAR(24) NOT NULL,
    ses_code CHAR(12) NULL,
    mail_code CHAR(12) NULL
);
```
Убедитесь что вы ввели данные от базы данных mysql:
```
conf.php
$dbhost = 'localhost'; // DATABASE_HOST
$dbname = ''; // DATABASE_USER
$dbpass = '' // DATABASE_PASSWORD
$dbname = 'user' // DATABASE_NAME, может быть любое
 
$conn = new mysqli(
  $dbhost,
  $dbuser,
  $dbpass,
  $dbname
);
if($conn -> connect_error){
  die('connection failed' . $conn -> connect_error);
}
```



## Запуск проекта

Запустите run.bat (сделает все за вас, в том числе установит composer)

Откройте браузер и перейдите по адресу http://localhost:5500.

## Использование

Регистрация: Перейдите на страницу регистрации и заполните форму.
Авторизация: После регистрации вы можете войти в систему, используя свои учетные данные.
Восстановление пароля: Если вы забыли пароль, перейдите на страницу восстановления пароля и введите свой адрес электронной почты. Вам будет отправлено письмо с инструкциями.

## Зависимости

PHP >= 7.2

Composer

PHPMailer
