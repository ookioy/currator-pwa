<?php
session_start();
require 'logic/db.php';
require 'logic/auth.php';

// Якщо вже авторизований - перенаправляємо на головну
if (checkAuth($pdo)) {
    header('Location: main.php');
    exit;
}

$error = '';
$success = false;

// Перевірка повідомлення після зміни пароля
if (isset($_GET['password_changed'])) {
    $success = true;
}

// Обробка форми входу
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    // Отримуємо хеш пароля з БД
    $stmt = $pdo->prepare("SELECT setting_value FROM config WHERE setting_name = 'admin_password'");
    $stmt->execute();
    $config = $stmt->fetch();

    // Перевіряємо пароль
    if ($config && password_verify($input_password, $config['setting_value'])) {
        // Пароль вірний - створюємо сесію
        $_SESSION['logged_in'] = true;

        // Обробка функції "Запам'ятати мене"
        if ($remember) {
            try {
                // Генеруємо безпечний токен
                $token = bin2hex(random_bytes(32));
                $token_hash = password_hash($token, PASSWORD_DEFAULT);
                
                // Зберігаємо токен в БД
                $stmt_token = $pdo->prepare("INSERT INTO auth_tokens (token_hash, created_at) VALUES (?, NOW())");
                $stmt_token->execute([$token_hash]);
                
                // Створюємо cookie на 30 днів
                setcookie('auth_token', $token, time() + (3600 * 24 * 30), "/", "", false, true);
            } catch (Exception $e) {
                // Якщо помилка - продовжуємо без "Запам'ятати мене"
            }
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
    <title>Вхід до системи</title>
</head>

<body>
    <main>
        <h1>Вхід до системи</h1>

        <!-- Повідомлення про успішну зміну пароля -->
        <?php if ($success): ?>
            <p><strong>Пароль успішно змінено! Увійдіть з новим паролем.</strong></p>
        <?php endif; ?>

        <!-- Повідомлення про помилку -->
        <?php if ($error): ?>
            <p><strong>Помилка: <?= htmlspecialchars($error) ?></strong></p>
        <?php endif; ?>

        <!-- Форма входу -->
        <form method="POST">
            <fieldset>
                <legend><strong>Авторизація</strong></legend>
                
                <p>
                    <label for="password">Пароль:</label><br>
                    <input type="password" 
                           id="password"
                           name="password" 
                           size="30"
                           required 
                           autofocus>
                </p>

                <p>
                    <label>
                        <input type="checkbox" name="remember">
                        Запам'ятати мене (30 днів)
                    </label>
                </p>

                <p>
                    <button type="submit">Увійти</button>
                </p>
            </fieldset>
        </form>
    </main>
</body>

</html>
