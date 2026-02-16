<?php
// Підключення до БД та перевірка авторизації
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$pageTitle = "Зміна пароля";
require 'blocks/header.php';
?>

<main>
    <!-- Навігація -->
    <p><a href="main.php">&larr; Назад до списку</a></p>
    
    <h2>Зміна пароля</h2>

    <!-- Повідомлення про успіх -->
    <?php if (isset($_GET['success'])): ?>
        <p><strong>Пароль успішно змінено!</strong> З міркувань безпеки всі активні сесії скинуто. Будь ласка, увійдіть заново.</p>
    <?php endif; ?>

    <!-- Повідомлення про помилки -->
    <?php if (isset($_GET['error'])): ?>
        <?php 
            // Масив помилок
            $errors = [
                'current' => 'Поточний пароль невірний!',
                'length' => 'Новий пароль повинен містити мінімум 6 символів!',
                'match' => 'Новий пароль та підтвердження не збігаються!',
                'same' => 'Новий пароль не може бути таким самим як старий!',
                'db' => 'Помилка бази даних при зміні пароля!'
            ];
            $errorMessage = $errors[$_GET['error']] ?? 'Невідома помилка!';
        ?>
        <p><strong>Помилка: <?= htmlspecialchars($errorMessage) ?></strong></p>
    <?php endif; ?>

    <!-- Форма зміни пароля -->
    <form method="POST" action="logic/update_password.php">
        <fieldset>
            <legend><strong>Зміна пароля адміністратора</strong></legend>
            
            <p>
                <label for="current_password">Поточний пароль:</label><br>
                <input type="password" 
                       id="current_password"
                       name="current_password" 
                       size="30"
                       required 
                       autofocus>
            </p>

            <p>
                <label for="new_password">Новий пароль:</label><br>
                <input type="password" 
                       id="new_password"
                       name="new_password" 
                       size="30"
                       required 
                       minlength="6">
                <br><small>(Мінімум 6 символів)</small>
            </p>

            <p>
                <label for="confirm_password">Підтвердіть новий пароль:</label><br>
                <input type="password" 
                       id="confirm_password"
                       name="confirm_password" 
                       size="30"
                       required 
                       minlength="6">
            </p>

            <p>
                <button type="submit">Змінити пароль</button>
            </p>
        </fieldset>
    </form>

    <!-- Важлива інформація -->
    <fieldset>
        <legend><strong>Важливо:</strong></legend>
        <ul>
            <li>Після зміни пароля всі активні сесії будуть завершені</li>
            <li>Вам потрібно буде увійти заново з новим паролем</li>
            <li>Збережіть новий пароль у безпечному місці</li>
        </ul>
    </fieldset>
</main>

<?php require 'blocks/footer.php'; ?>
