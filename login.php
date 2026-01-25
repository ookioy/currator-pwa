<?php
session_start();
require 'logic/db.php';
require 'logic/auth.php';

// Якщо вже авторизований - на головну
if (checkAuth($pdo)) {
    header('Location: main.php');
    exit;
}

$error = '';
$success = false;

// Повідомлення після зміни пароля
if (isset($_GET['password_changed'])) {
    $success = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    // Отримуємо хеш пароля з БД
    $stmt = $pdo->prepare("SELECT setting_value FROM config WHERE setting_name = 'admin_password'");
    $stmt->execute();
    $config = $stmt->fetch();

    // Перевіряємо пароль через password_verify
    if ($config && password_verify($input_password, $config['setting_value'])) {
        // Пароль вірний - створюємо сесію
        $_SESSION['logged_in'] = true;

        // Якщо вибрано "Запам'ятати мене"
        if ($remember) {
            try {
                // Генеруємо безпечний випадковий токен
                $token = bin2hex(random_bytes(32));
                
                // Хешуємо токен для зберігання в БД
                $token_hash = password_hash($token, PASSWORD_DEFAULT);
                
                // Зберігаємо хеш токена в БД
                $stmt_token = $pdo->prepare("INSERT INTO auth_tokens (token_hash, created_at) VALUES (?, NOW())");
                $stmt_token->execute([$token_hash]);
                
                // Зберігаємо оригінальний токен в cookie (не хеш!)
                setcookie('auth_token', $token, time() + (3600 * 24 * 30), "/", "", false, true);
            } catch (Exception $e) {
                // Якщо помилка з токеном - не критично, просто не запам'ятовуємо
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід</title>
</head>

<body>
    <main>
        <h1>Вхід до системи</h1>

        <?php if ($success): ?>
            <p><strong>✅ Пароль успішно змінено! Увійдіть з новим паролем.</strong></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p><strong>❌ <?= htmlspecialchars($error) ?></strong></p>
        <?php endif; ?>

        <form method="POST">
            <fieldset>
                <legend>Авторизація</legend>
                
                <p>
                    <label for="password">Пароль:</label><br>
                    <input type="password" 
                           id="password"
                           name="password" 
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