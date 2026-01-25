<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

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
    // Прибираємо всі символи крім цифр та +
    $cleaned = preg_replace('/[^\d+]/', '', $phone);
    // Перевіряємо чи є достатньо цифр (мінімум 10)
    return strlen($cleaned) >= 10;
}

// Функція для форматування телефону для дзвінка
function formatPhoneForCall($phone) {
    return 'tel:' . preg_replace('/[^\d+]/', '', $phone);
}

$pageTitle = "Профіль: " . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <a href="main.php">← До списку</a>
    <h1>Перегляд та редагування студента</h1>

    <?php if (isset($_GET['updated'])): ?>
        <p style="color: green;"><b>✅ Зміни збережено!</b></p>
    <?php endif; ?>

    <form action="logic/update_student.php" method="POST" onsubmit="return confirm('Зберегти зміни?');">
        
        <input type="hidden" name="id" value="<?= $student['id'] ?>">

        <h3>Дані студента</h3>

        <p>
            <label>ПІБ:</label><br>
            <input type="text" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" required>
        </p>

        <p>
            <label>Телефон:</label><br>
            <input type="text" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
            <?php if (!empty($student['phone'])): ?>
                <?php if (isValidPhone($student['phone'])): ?>
                    <a href="<?= formatPhoneForCall($student['phone']) ?>">
                        <button type="button">📞 Зателефонувати</button>
                    </a>
                <?php else: ?>
                    <button type="button" onclick="alert('⚠️ Номер телефону не є дійсним!')">📞 Зателефонувати</button>
                <?php endif; ?>
            <?php endif; ?>
        </p>

        <p>
            <label>Дата народження:</label><br>
            <input type="date" name="birth_date" value="<?= htmlspecialchars($student['birth_date'] ?? '') ?>">
        </p>

        <p>
            <label>Адреса реєстрації:</label><br>
            <input type="text" name="home_address" value="<?= htmlspecialchars($student['home_address'] ?? '') ?>">
        </p>

        <p>
            <label>Фактична адреса:</label><br>
            <input type="text" name="actual_address" value="<?= htmlspecialchars($student['actual_address'] ?? '') ?>">
        </p>

        <p>
            <label>Освіта:</label><br>
            <input type="text" name="education" value="<?= htmlspecialchars($student['education'] ?? '') ?>">
        </p>

        <p>
            <label>Мови:</label><br>
            <input type="text" name="languages" value="<?= htmlspecialchars($student['languages'] ?? '') ?>">
        </p>

        <p>
            <label>Джерело інформації:</label><br>
            <input type="text" name="info_source" value="<?= htmlspecialchars($student['info_source'] ?? '') ?>">
        </p>

        <p>
            <label>Кар'єрна ціль:</label><br>
            <input type="text" name="career_goal" value="<?= htmlspecialchars($student['career_goal'] ?? '') ?>">
        </p>

        <p>
            <label>Мови програмування:</label><br>
            <input type="text" name="programming_languages" value="<?= htmlspecialchars($student['programming_languages'] ?? '') ?>">
        </p>

        <p>
            <label>Хобі/Інтереси:</label><br>
            <textarea name="activities" rows="3"><?= htmlspecialchars($student['activities'] ?? '') ?></textarea>
        </p>

        <p>
            <label>
                <input type="checkbox" name="has_experience" value="1" <?= $student['has_experience'] ? 'checked' : '' ?>> Має досвід роботи
            </label>
        </p>

        <button type="submit">Зберегти зміни студента</button>
    </form>

    <hr>

    <h3>Батьки/Опікуни</h3>
    
    <?php if (empty($parents)): ?>
        <p>Дані батьків не додано.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ПІБ</th>
                    <th>Тип</th>
                    <th>Місце роботи</th>
                    <th>Телефон</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($parents as $parent): ?>
                <tr>
                    <td><?= htmlspecialchars($parent['full_name']) ?></td>
                    <td><?= htmlspecialchars($parent['type']) ?></td>
                    <td><?= htmlspecialchars($parent['work_info'] ?? '') ?></td>
                    <td>
                        <?= htmlspecialchars($parent['phone'] ?? '') ?>
                        <?php if (!empty($parent['phone'])): ?>
                            <br>
                            <?php if (isValidPhone($parent['phone'])): ?>
                                <a href="<?= formatPhoneForCall($parent['phone']) ?>">
                                    <button type="button">📞 Зателефонувати</button>
                                </a>
                            <?php else: ?>
                                <button type="button" onclick="alert('⚠️ Номер телефону не є дійсним!')">📞 Зателефонувати</button>
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
</main>

<?php require 'blocks/footer.php'; ?>