<?php
// Підключення до БД та перевірка авторизації
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

// Отримуємо пошуковий запит
$search_query = $_GET['full-name'] ?? '';
$results = [];

// Якщо є запит - виконуємо пошук
if ($search_query) {
    $stmt = $pdo->prepare("SELECT id, full_name, phone FROM students WHERE full_name LIKE ? ORDER BY full_name ASC");
    $stmt->execute(['%' . $search_query . '%']);
    $results = $stmt->fetchAll();
}

$pageTitle = "Пошук студентів";
require 'blocks/header.php';
?>

<main>
    <!-- Навігація -->
    <p><a href="main.php">&larr; До списку</a></p>

    <h2>Результати пошуку</h2>

    <?php if ($search_query): ?>
        <p>Пошук за запитом: <strong><?= htmlspecialchars($search_query) ?></strong></p>

        <?php if (empty($results)): ?>
            <!-- Нічого не знайдено -->
            <p><em>Нічого не знайдено. Спробуйте інший запит.</em></p>
        <?php else: ?>
            <!-- Результати знайдено -->
            <p>Знайдено: <strong><?= count($results) ?></strong> студентів</p>

            <!-- Таблиця результатів -->
            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <thead>
                    <tr bgcolor="#e0e0e0">
                        <th align="left" width="50%">ПІБ</th>
                        <th align="left" width="30%">Телефон</th>
                        <th align="center" width="20%">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $student): ?>
                        <tr>
                            <!-- ПІБ з посиланням -->
                            <td>
                                <a href="view_student.php?id=<?= $student['id'] ?>">
                                    <strong><?= htmlspecialchars($student['full_name']) ?></strong>
                                </a>
                            </td>

                            <!-- Телефон -->
                            <td><?= htmlspecialchars($student['phone'] ?? '—') ?></td>

                            <!-- Кнопка видалення -->
                            <td align="center">
                                <form action="logic/delete_student.php" method="POST" onsubmit="return confirm('Ви впевнені, що хочете видалити студента <?= htmlspecialchars($student['full_name']) ?>? Також будуть видалені всі дані про батьків!');">
                                    <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                    <button type="submit">Видалити</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php else: ?>
        <!-- Запит не введено -->
        <p><em>Введіть запит для пошуку у формі вище.</em></p>
    <?php endif; ?>
</main>

<?php require 'blocks/footer.php'; ?>
