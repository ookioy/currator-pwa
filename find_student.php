<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$search_query = $_GET['full-name'] ?? '';
$results = [];

if ($search_query) {
    $stmt = $pdo->prepare("SELECT id, full_name, phone FROM students WHERE full_name LIKE ? ORDER BY full_name ASC");
    $stmt->execute(['%' . $search_query . '%']);
    $results = $stmt->fetchAll();
}

$pageTitle = "Пошук студентів";
require 'blocks/header.php';
?>

<main>
    <a href="main.php">До списку</a>
    <h1>Результати пошуку</h1>

    <?php if ($search_query): ?>
        <p>Пошук за запитом: <strong><?= htmlspecialchars($search_query) ?></strong></p>

        <?php if (empty($results)): ?>
            <p>Нічого не знайдено.</p>
        <?php else: ?>
            <p>Знайдено: <?= count($results) ?> студентів</p>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>ПІБ</th>
                        <th>Телефон</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $student): ?>
                        <tr>
                            <td>
                                <a href="view_student.php?id=<?= $student['id'] ?>">
                                    <?= htmlspecialchars($student['full_name']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($student['phone'] ?? '—') ?></td>
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
    <?php else: ?>
        <p>Введіть запит для пошуку.</p>
    <?php endif; ?>
</main>

<?php require 'blocks/footer.php'; ?>