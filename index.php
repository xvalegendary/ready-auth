<?php session_start(); ?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(168, 168, 168);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .main-cont {
            width: 155px;
            height: 250px;   
        }

        button{
            padding:5px;
            background:black;
            color:white;
            outline:None;
            border: none;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            transition:linear 0.2s;

        }
        button:hover{
            color:#333;
            background:aliceblue;
        }
        div div form button{
            width:145px;
        }
        div button{
            width:145px;
        }
        div div form input{
            outline:none;
            background:#333;
            border:none;
            width:141px;
            color:white;
        }



        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="main-cont">
        <div id="auth-form">
            <h2>Авторизация</h2>
            <form id="login-form">
                <input type="text" name="login" placeholder="Логин" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit">Войти</button>
            </form>
        </div>

        <div id="reg-form" class="hidden">
            <h2>Регистрация</h2>
            <form id="registration-form">
                <input type="text" name="login" placeholder="Логин" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <input type="text" name="last_name" placeholder="Фамилия" required>
                <input type="text" name="sur_name" placeholder="Имя" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="number" name="year_birth" placeholder="Год рождения" required>
                <label>Пол:</label>
                <input type="radio" name="gender" value="M" required> Мужской
                <input type="radio" name="gender" value="F" required> Женский
                <button type="submit">Зарегистрироваться</button>
                <button name="backtolog" type="button" id="back-to-login">Вернуться к авторизации</button>
            </form>
        </div>

        <div id="reset-form" class="hidden">
            <h2>Восстановление пароля</h2>
            <form id="reset-password-form">
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">Восстановить пароль</button>
                <button name="backtolog" type="button" id="reload-btn">Вернуться к авторизации</button>
            </form>
            <div id="verify-code-form" class="hidden">
                <h3>Введите код восстановления</h3>
                <input type="text" id="mail_code" placeholder="Код из письма" required>
                <input type="password" id="new_password" placeholder="Новый пароль" required>
                <button id="verify-code-btn">Подтвердить</button>
            </div>
        </div>

        <button id="show-register">Регистрация</button>
        <button id="show-reset" class="hidden">Восстановление пароля</button>

    </div>
    <script>
        $(document).ready(function() {
            $('#show-register').click(function() {
                $('#auth-form').addClass('hidden');
                $('#reg-form').removeClass('hidden');
                $('#show-register').addClass('hidden');
                $('#show-reset').removeClass('hidden');
            });

            $('#show-reset').click(function() {
                $('#auth-form').addClass('hidden');
                $('#reg-form').addClass("hidden");
                $('#reset-form').removeClass('hidden');
                $('#show-reset').addClass('hidden');
                $('#show-register').addClass('hidden');

            });

            $('#back-to-login').click(function() {
                $('#reg-form').addClass('hidden');
                $('#show-register').addClass('hidden');
                $('#reset-form').addClass('hidden');
                $('#auth-form').removeClass('hidden');
            });

            $('#reload-btn').click(function() {
                location.reload();
            });




            $('#login-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'server.php',
                    data: $(this).serialize() + '&action=login',
                    success: function(response) {
                        const res = JSON.parse(response);
                        alert(res.message);
                        if (res.status === 'success') {
                            window.location = 'user.php';
                        }
                    }
                });
            });

            $('#registration-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'server.php',
                    data: $(this).serialize() + '&action=register',
                    success: function(response) {
                        const res = JSON.parse(response);
                        alert(res.message);
                        if (res.status === 'success') {
                            $('#reg-form').addClass('hidden');
                            $('#auth-form').removeClass('hidden');
                        }
                    }
                });
            });

            $('#reset-password-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'server.php',
                    data: $(this).serialize() + '&action=reset',
                    success: function(response) {
                        const res = JSON.parse(response);
                        alert(res.message);
                        if (res.status === 'success') {
                            $('#verify-code-form').removeClass('hidden');
                        }
                    }
                });
            });

            $('#verify-code-btn').click(function() {
                const email = $('#reset-password-form input[name="email"]').val();
                const mail_code = $('#mail_code').val();
                const new_password = $('#new_password').val();

                $.ajax({
                    type: 'POST',
                    url: 'server.php',
                    data: {
                        email: email,
                        code: mail_code,
                        new_password: new_password,
                        action: 'verify_code'
                    },
                    success: function(response) {
                        const res = JSON.parse(response);
                        alert(res.message);
                        if (res.status === 'success') {
                            $('#verify-code-form').addClass('hidden');
                            $('#reset-form').addClass('hidden');
                            $('#auth-form').removeClass('hidden');
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>