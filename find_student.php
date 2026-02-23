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
    <p><a href="main.php">&larr; До списку</a></p>
    <h2>Результати пошуку</h2>

    <?php if ($search_query): ?>
        <p>Пошук за запитом: <strong><?= htmlspecialchars($search_query) ?></strong></p>

        <?php if (empty($results)): ?>
            <p><em>Нічого не знайдено. Спробуйте інший запит.</em></p>
        <?php else: ?>
            <p>Знайдено: <strong><?= count($results) ?></strong> студентів</p>

            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th align="left">ПІБ Студента</th>
                        <th align="left">Телефон</th>
                        <th align="center" width="150">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $s): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($s['full_name']) ?></strong></td>
                            <td>
                                <?= htmlspecialchars($s['phone'] ?? '—') ?>
                                <?php if (!empty($s['phone'])): ?>
                                    <a href="tel:<?= preg_replace('/[^\d+]/', '', $s['phone']) ?>">📞</a>
                                <?php endif; ?>
                            </td>
                            <td align="center">
                                <a href="view_student.php?id=<?= $s['id'] ?>" class="action-btn btn-view" title="Переглянути деталі">
                                    <i class="fa-solid fa-eye fa-lg"></i>
                                </a>
                                <a href="edit_student.php?id=<?= $s['id'] ?>" class="action-btn btn-edit" title="Редагувати">
                                    <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                </a>
                                <form action="logic/delete_student.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="student_id" value="<?= $s['id'] ?>">
                                    <button type="submit" class="action-btn btn-delete" title="Видалити">
                                        <i class="fa-solid fa-trash fa-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php else: ?>
        <p><em>Введіть запит для пошуку у формі вище.</em></p>
    <?php endif; ?>
</main>

<?php require 'blocks/footer.php'; ?>