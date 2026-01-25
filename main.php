<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$stmt = $pdo->query("SELECT id, full_name, phone FROM students ORDER BY full_name ASC");
$students = $stmt->fetchAll();

$pageTitle = "Головна - Список групи";
require 'blocks/header.php';
?>

<main>
    <h2>Список групи</h2>

    <?php if (isset($_GET['success'])): ?>
        <p><b>Студента успішно додано!</b></p>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <p><b>Студента успішно видалено!</b></p>
    <?php endif; ?>

    <?php if (empty($students)): ?>
        <p>Студентів ще не додано.</p>
    <?php else: ?>
        <p>Всього студентів: <strong><?= count($students) ?></strong></p>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ПІБ Студента</th>
                    <th>Телефон</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td>
                        <a href="view_student.php?id=<?= $s['id'] ?>">
                            <?= htmlspecialchars($s['full_name']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($s['phone'] ?? '—') ?></td>
                    <td>
                        <form action="logic/delete_student.php" method="POST" style="display: inline;" onsubmit="return confirm('Ви впевнені, що хочете видалити студента <?= htmlspecialchars($s['full_name']) ?>? Також будуть видалені всі дані про батьків!');">
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

<?php
require 'blocks/footer.php';
?>