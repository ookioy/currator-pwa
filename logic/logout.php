<?php
// Підключення до БД
session_start();
require 'db.php';

// Видаляємо токен з БД якщо він є
if (isset($_COOKIE['auth_token'])) {
    try {
        $token = $_COOKIE['auth_token'];
        
        // Знаходимо і видаляємо токен
        $stmt = $pdo->prepare("SELECT id, token_hash FROM auth_tokens");
        $stmt->execute();
        $tokens = $stmt->fetchAll();
        
        foreach ($tokens as $row) {
            if (password_verify($token, $row['token_hash'])) {
                $delete = $pdo->prepare("DELETE FROM auth_tokens WHERE id = ?");
                $delete->execute([$row['id']]);
                break;
            }
        }
    } catch (Exception $e) {
        // Ігноруємо помилки при logout
    }
    
    // Видаляємо cookie
    setcookie('auth_token', '', time() - 3600, "/");
}

// Знищуємо сесію
session_destroy();

// Перенаправляємо на логін (тепер треба піднятись на рівень вище)
header('Location: ../login.php');
exit;
