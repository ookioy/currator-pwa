<?php
// Підключення до БД та перевірка авторизації
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

// Отримання ID студента
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: main.php');
    exit;
}

// Отримуємо дані студента
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Студента не знайдено!");
}

// Отримуємо батьків студента
$stmt_parents = $pdo->prepare("SELECT * FROM parents WHERE student_id = ? ORDER BY type");
$stmt_parents->execute([$id]);
$parents = $stmt_parents->fetchAll();

// Функція для валідації телефону
function isValidPhone($phone) {
    if (empty($phone)) return false;
    $cleaned = preg_replace('/[^\d+]/', '', $phone);
    return strlen($cleaned) >= 10;
}

// Функція для форматування телефону
function formatPhoneForCall($phone) {
    return 'tel:' . preg_replace('/[^\d+]/', '', $phone);
}

$pageTitle = "Профіль: " . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <!-- Навігація -->
    <p><a href="main.php">&larr; До списку</a></p>

    <h2>Перегляд та редагування студента</h2>

    <!-- Повідомлення про успіх -->
    <?php if (isset($_GET['updated'])): ?>
        <p><strong>Зміни збережено!</strong></p>
    <?php endif; ?>

    <!-- Форма редагування студента -->
    <form action="logic/update_student.php" method="POST" onsubmit="return confirm('Зберегти зміни?');">
        <input type="hidden" name="id" value="<?= $student['id'] ?>">

        <fieldset>
            <legend><strong>Дані студента</strong></legend>

            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td width="30%"><label for="full_name">ПІБ:</label></td>
                    <td><input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" size="50" required></td>
                </tr>
                
                <tr>
                    <td><label for="phone">Телефон:</label></td>
                    <td>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>" size="30">
                        <?php if (!empty($student['phone'])): ?>
                            <?php if (isValidPhone($student['phone'])): ?>
                                <a href="<?= formatPhoneForCall($student['phone']) ?>">
                                    <button type="button">Зателефонувати</button>
                                </a>
                            <?php else: ?>
                                <button type="button" onclick="alert('Номер телефону не є дійсним!')">Зателефонувати</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td><label for="birth_date">Дата народження:</label></td>
                    <td><input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($student['birth_date'] ?? '') ?>"></td>
                </tr>

                <tr>
                    <td><label for="home_address">Адреса реєстрації:</label></td>
                    <td><input type="text" id="home_address" name="home_address" value="<?= htmlspecialchars($student['home_address'] ?? '') ?>" size="60"></td>
                </tr>

                <tr>
                    <td><label for="actual_address">Фактична адреса:</label></td>
                    <td><input type="text" id="actual_address" name="actual_address" value="<?= htmlspecialchars($student['actual_address'] ?? '') ?>" size="60"></td>
                </tr>

                <tr>
                    <td><label for="education">Освіта:</label></td>
                    <td><input type="text" id="education" name="education" value="<?= htmlspecialchars($student['education'] ?? '') ?>" size="50"></td>
                </tr>

                <tr>
                    <td><label for="languages">Мови:</label></td>
                    <td><input type="text" id="languages" name="languages" value="<?= htmlspecialchars($student['languages'] ?? '') ?>" size="40"></td>
                </tr>

                <tr>
                    <td><label for="info_source">Джерело інформації:</label></td>
                    <td><input type="text" id="info_source" name="info_source" value="<?= htmlspecialchars($student['info_source'] ?? '') ?>" size="40"></td>
                </tr>

                <tr>
                    <td><label for="career_goal">Кар'єрна ціль:</label></td>
                    <td><input type="text" id="career_goal" name="career_goal" value="<?= htmlspecialchars($student['career_goal'] ?? '') ?>" size="50"></td>
                </tr>

                <tr>
                    <td><label for="programming_languages">Мови програмування:</label></td>
                    <td><input type="text" id="programming_languages" name="programming_languages" value="<?= htmlspecialchars($student['programming_languages'] ?? '') ?>" size="50"></td>
                </tr>

                <tr>
                    <td valign="top"><label for="activities">Хобі/Інтереси:</label></td>
                    <td><textarea id="activities" name="activities" rows="3" cols="60"><?= htmlspecialchars($student['activities'] ?? '') ?></textarea></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <label>
                            <input type="checkbox" name="has_experience" value="1" <?= $student['has_experience'] ? 'checked' : '' ?>>
                            Має досвід роботи
                        </label>
                    </td>
                </tr>
            </table>

            <p>
                <button type="submit"><strong>Зберегти зміни студента</strong></button>
            </p>
        </fieldset>
    </form>

    <br>

    <!-- Секція з батьками -->
    <fieldset>
        <legend><strong>Батьки/Опікуни</strong></legend>

        <?php if (empty($parents)): ?>
            <p><em>Дані батьків не додано.</em></p>
        <?php else: ?>
            <!-- Таблиця з батьками -->
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr bgcolor="#e0e0e0">
                        <th align="left" width="30%">ПІБ</th>
                        <th align="left" width="15%">Тип</th>
                        <th align="left" width="30%">Місце роботи</th>
                        <th align="left" width="25%">Телефон</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($parents as $parent): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($parent['full_name']) ?></strong></td>
                        <td><?= $parent['type'] === 'mother' ? 'Мати' : 'Батько' ?></td>
                        <td><?= htmlspecialchars($parent['work_info'] ?? '—') ?></td>
                        <td>
                            <?= htmlspecialchars($parent['phone'] ?? '—') ?>
                            <?php if (!empty($parent['phone'])): ?>
                                <br>
                                <?php if (isValidPhone($parent['phone'])): ?>
                                    <a href="<?= formatPhoneForCall($parent['phone']) ?>">
                                        <button type="button">Зателефонувати</button>
                                    </a>
                                <?php else: ?>
                                    <button type="button" onclick="alert('Номер телефону не є дійсним!')">Зателефонувати</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <p>
            <a href="edit_parents.php?student_id=<?= $student['id'] ?>">
                <button type="button">Редагувати батьків/опікунів</button>
            </a>
        </p>
    </fieldset>
</main>

<?php require 'blocks/footer.php'; ?>
