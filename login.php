<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT setting_value FROM config WHERE setting_name = 'admin_password'");
    $stmt->execute();
    $config = $stmt->fetch();

    if ($config && $input_password === $config['setting_value']) {
        $_SESSION['logged_in'] = true;
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
        <h2>Вхід куратора</h2>
        <input type="password" name="password" placeholder="Пароль" required><br><br>
        <button type="submit" style="padding:10px 20px; cursor:pointer;">Увійти</button>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    </form>
</body>
</html>