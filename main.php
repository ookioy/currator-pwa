<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$stmt = $pdo->query("SELECT id, full_name FROM students ORDER BY full_name ASC");
$students = $stmt->fetchAll();

$pageTitle = "Головна - Список групи";
require 'blocks/header.php';
?>

<main>
    <h2>Список групи</h2>

    <?php if (empty($students)): ?>
        <p>Студентів ще не додано.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ПІБ Студента</th>
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
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php
require 'blocks/footer.php';
?>