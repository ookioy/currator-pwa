<?php
// Підключення до бази даних та перевірка авторизації
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

// Отримуємо список всіх студентів
$stmt = $pdo->query("SELECT id, full_name, phone FROM students ORDER BY full_name ASC");
$students = $stmt->fetchAll();

$pageTitle = "Головна - Список групи";
require 'blocks/header.php';
?>

<main>
    <h2>Список групи</h2>

    <!-- Повідомлення про успішні дії -->
    <?php if (isset($_GET['success'])): ?>
        <p><strong>Студента успішно додано!</strong></p>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <p><strong>Студента успішно видалено!</strong></p>
    <?php endif; ?>

    <!-- Перевірка чи є студенти -->
    <?php if (empty($students)): ?>
        <p><em>Студентів ще не додано.</em></p>
    <?php else: ?>
        <p>Всього студентів: <strong><?= count($students) ?></strong></p>
        
        <!-- Таблиця зі списком студентів -->
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr bgcolor="#e0e0e0">
                    <th align="left" width="50%">ПІБ Студента</th>
                    <th align="left" width="30%">Телефон</th>
                    <th align="center" width="20%">Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <!-- ПІБ з посиланням на профіль -->
                    <td>
                        <a href="view_student.php?id=<?= $s['id'] ?>">
                            <strong><?= htmlspecialchars($s['full_name']) ?></strong>
                        </a>
                    </td>
                    
                    <!-- Телефон -->
                    <td><?= htmlspecialchars($s['phone'] ?? '—') ?></td>
                    
                    <!-- Кнопка видалення -->
                    <td align="center">
                        <form action="logic/delete_student.php" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити студента <?= htmlspecialchars($s['full_name']) ?>? Також будуть видалені всі дані про батьків!');">
                            <input type="hidden" name="student_id" value="<?= $s['id'] ?>">
                            <button type="submit">Видалити</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require 'blocks/footer.php'; ?>
