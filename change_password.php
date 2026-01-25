<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$pageTitle = "Зміна пароля";
require 'blocks/header.php';
?>

<main>
    <a href="main.php">← Назад до списку</a>
    <h1>Зміна пароля</h1>

    <?php if (isset($_GET['success'])): ?>
        <p><strong>Пароль успішно змінено!</strong></p>
        <p>З міркувань безпеки всі активні сесії скинуто. Будь ласка, увійдіть заново.</p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <p><strong>❌ Помилка:</strong></p>
        <p>
            <?php 
                $errors = [
                    'current' => 'Поточний пароль невірний!',
                    'length' => 'Новий пароль повинен містити мінімум 6 символів!',
                    'match' => 'Новий пароль та підтвердження не збігаються!',
                    'same' => 'Новий пароль не може бути таким самим як старий!',
                    'db' => 'Помилка бази даних при зміні пароля!'
                ];
                echo htmlspecialchars($errors[$_GET['error']] ?? 'Невідома помилка!');
            ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="logic/update_password.php">
        <fieldset>
            <legend>Зміна пароля адміністратора</legend>
            
            <p>
                <label for="current_password">Поточний пароль:</label><br>
                <input type="password" 
                       id="current_password"
                       name="current_password" 
                       required 
                       autofocus>
            </p>

            <p>
                <label for="new_password">Новий пароль:</label><br>
                <input type="password" 
                       id="new_password"
                       name="new_password" 
                       required 
                       minlength="6">
                <br><small>(Мінімум 6 символів)</small>
            </p>

            <p>
                <label for="confirm_password">Підтвердіть новий пароль:</label><br>
                <input type="password" 
                       id="confirm_password"
                       name="confirm_password" 
                       required 
                       minlength="6">
            </p>

            <p>
                <button type="submit">Змінити пароль</button>
            </p>
        </fieldset>
    </form>

    <section>
        <h3>Важливо:</h3>
        <ul>
            <li>Після зміни пароля всі активні сесії будуть завершені</li>
            <li>Вам потрібно буде увійти заново з новим паролем</li>
            <li>Збережіть новий пароль у безпечному місці</li>
        </ul>
    </section>
</main>

<?php require 'blocks/footer.php'; ?>