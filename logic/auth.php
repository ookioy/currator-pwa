<?php
session_start();

function checkAuth($pdo) {
    // Спочатку перевіряємо сесію
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }

    // Якщо сесії немає - перевіряємо cookie токен "Запам'ятати мене"
    if (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        
        try {
            // Отримуємо всі активні токени (створені за останні 30 днів)
            $stmt = $pdo->prepare("SELECT token_hash FROM auth_tokens WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $stmt->execute();
            $tokens = $stmt->fetchAll();
            
            // Перевіряємо чи співпадає токен з cookie з будь-яким хешем в БД
            foreach ($tokens as $row) {
                if (password_verify($token, $row['token_hash'])) {
                    // Токен валідний - авторизуємо користувача
                    $_SESSION['logged_in'] = true;
                    return true;
                }
            }
            
            // Якщо токен не знайдено або застарів - видаляємо cookie
            setcookie('auth_token', '', time() - 3600, "/");
            
        } catch (Exception $e) {
            // При помилці БД - видаляємо cookie
            setcookie('auth_token', '', time() - 3600, "/");
        }
    }
    
    return false;
}

function protectPage($pdo) {
    if (!checkAuth($pdo)) {
        header('Location: login.php');
        exit;
    }
}
