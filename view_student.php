<?php
require 'logic/db.php';
require 'logic/auth.php';
protectPage($pdo);

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: main.php'); exit; }

// Отримуємо студента
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) die("Студента не знайдено!");

// Отримуємо батьків
$stmt_parents = $pdo->prepare("SELECT * FROM parents WHERE student_id = ?");
$stmt_parents->execute([$id]);
$parents = $stmt_parents->fetchAll();

$pageTitle = "Перегляд: " . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <p><a href="main.php" style="text-decoration: none;">&larr; Назад до списку</a></p>
        
        <a href="edit_student.php?id=<?= $student['id'] ?>">
            <button style="background: #FFC107; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px;">
                <i class="fa-solid fa-pen"></i> Редагувати профіль
            </button>
        </a>
    </div>

    <h2>Особова картка студента</h2>

    <?php if (isset($_GET['updated'])): ?>
        <p style="color: green; background: #eaffea; padding: 10px; border: 1px solid green;">Дані успішно оновлено!</p>
    <?php endif; ?>

    <fieldset>
        <legend><strong>Основна інформація</strong></legend>
        <table border="0" cellpadding="8" cellspacing="0" width="100%">
            <tr>
                <td width="30%"><strong>ПІБ:</strong></td>
                <td><?= htmlspecialchars($student['full_name']) ?></td>
            </tr>
            <tr>
                <td><strong>Телефон:</strong></td>
                <td>
                    <?= htmlspecialchars($student['phone'] ?? '—') ?>
                    <?php if(!empty($student['phone'])): ?>
                        <a href="tel:<?= preg_replace('/[^\d+]/', '', $student['phone']) ?>" style="text-decoration: none;">📞</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Дата народження:</strong></td>
                <td><?= htmlspecialchars($student['birth_date'] ?? '—') ?></td>
            </tr>
            <tr>
                <td><strong>Адреса реєстрації:</strong></td>
                <td><?= htmlspecialchars($student['home_address'] ?? '—') ?></td>
            </tr>
            <tr>
                <td><strong>Фактична адреса:</strong></td>
                <td><?= htmlspecialchars($student['actual_address'] ?? '—') ?></td>
            </tr>
            <tr>
                <td><strong>Освіта:</strong></td>
                <td><?= htmlspecialchars($student['education'] ?? '—') ?></td>
            </tr>
            <tr>
                <td><strong>Мови:</strong></td>
                <td><?= htmlspecialchars($student['languages'] ?? '—') ?></td>
            </tr>
            <tr>
                <td><strong>Хобі/Інтереси:</strong></td>
                <td><?= nl2br(htmlspecialchars($student['activities'] ?? '—')) ?></td>
            </tr>
             <tr>
                <td><strong>Досвід роботи:</strong></td>
                <td><?= $student['has_experience'] ? '✅ Є досвід' : '❌ Немає досвіду' ?></td>
            </tr>
        </table>
    </fieldset>

    <br>

    <fieldset>
        <legend><strong>Батьки / Опікуни</strong></legend>
        <?php if (count($parents) > 0): ?>
            <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr style="background: #f0f0f0;">
                    <th>Роль</th>
                    <th>ПІБ</th>
                    <th>Робота</th>
                    <th>Телефон</th>
                </tr>
                <?php foreach ($parents as $p): ?>
                <tr>
                    <td><?= $p['type'] === 'father' ? 'Батько' : ($p['type'] === 'mother' ? 'Мати' : 'Опікун') ?></td>
                    <td><?= htmlspecialchars($p['full_name']) ?></td>
                    <td><?= htmlspecialchars($p['work_info'] ?? '—') ?></td>
                    <td>
                        <?= htmlspecialchars($p['phone'] ?? '—') ?>
                        <?php if(!empty($p['phone'])): ?>
                            <a href="tel:<?= preg_replace('/[^\d+]/', '', $p['phone']) ?>" style="text-decoration: none;">📞</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p><em>Інформація про батьків відсутня.</em></p>
        <?php endif; ?>
    </fieldset>

</main>
<?php require 'blocks/footer.php'; ?>