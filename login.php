<?php
session_start();
require 'logic/db.php';
require 'logic/auth.php';

if (checkAuth($pdo)) {
    header('Location: main.php');
    exit;
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: main.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    $stmt = $pdo->prepare("SELECT setting_value FROM config WHERE setting_name = 'admin_password'");
    $stmt->execute();
    $config = $stmt->fetch();

    if ($config && $input_password === $config['setting_value']) {
        $_SESSION['logged_in'] = true;

        if ($remember) {
            $token = md5($config['setting_value'] . 'salt123');
            setcookie('auth_token', $token, time() + (3600 * 24 * 30), "/");
        }

        header('Location: main.php');
        exit;
    } else {
        $error = 'Невірний пароль!';
    }
}
?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід</title>
</head>

<body>
    <form method="POST">
        <h2>Вхід</h2>
        <input type="password" name="password" placeholder="Пароль" required><br>

        <label>
            <input type="checkbox" name="remember"> Запам'ятати мене
        </label><br><br>

        <button type="submit">Увійти</button>
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    </form>
</body>

</html>