<?php
require 'db.php';
require 'auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Отримуємо поточний хеш пароля з БД
    $stmt = $pdo->prepare("SELECT setting_value FROM config WHERE setting_name = 'admin_password'");
    $stmt->execute();
    $config = $stmt->fetch();
    
    // Перевірка 1: Чи існує пароль в БД
    if (!$config) {
        header('Location: ../change_password.php?error=db');
        exit;
    }
    
    // Перевірка 2: Чи вірний поточний пароль
    if (!password_verify($current_password, $config['setting_value'])) {
        header('Location: ../change_password.php?error=current');
        exit;
    }
    
    // Перевірка 3: Довжина нового пароля
    if (strlen($new_password) < 6) {
        header('Location: ../change_password.php?error=length');
        exit;
    }
    
    // Перевірка 4: Збіг нового пароля та підтвердження
    if ($new_password !== $confirm_password) {
        header('Location: ../change_password.php?error=match');
        exit;
    }
    
    // Перевірка 5: Новий пароль не повинен бути таким самим як старий
    if (password_verify($new_password, $config['setting_value'])) {
        header('Location: ../change_password.php?error=same');
        exit;
    }
    
    // Все ОК - зберігаємо новий пароль
    try {
        $pdo->beginTransaction();
        
        // Хешуємо новий пароль
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Оновлюємо пароль в БД
        $stmt_update = $pdo->prepare("UPDATE config SET setting_value = ? WHERE setting_name = 'admin_password'");
        $stmt_update->execute([$new_password_hash]);
        
        // Видаляємо всі токени "Запам'ятати мене" для безпеки
        $pdo->exec("DELETE FROM auth_tokens");
        
        $pdo->commit();
        
        // Видаляємо cookie поточного користувача
        if (isset($_COOKIE['auth_token'])) {
            setcookie('auth_token', '', time() - 3600, "/");
        }
        
        // Знищуємо сесію - користувач має увійти заново
        session_destroy();
        
        // Перенаправляємо на логін з повідомленням про успіх
        header('Location: ../login.php?password_changed=1');
        exit;
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        header('Location: ../change_password.php?error=db');
        exit;
    }
}

// Якщо не POST запит - повертаємо на форму
header('Location: ../change_password.php');
exit;
