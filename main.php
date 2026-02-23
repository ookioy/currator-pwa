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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Стилі для кнопок дій */
    .action-btn {
        text-decoration: none;
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 4px;
        display: inline-block;
        transition: opacity 0.3s;
    }
    .action-btn:hover {
        opacity: 0.8;
    }
    /* Кольори кнопок */
    .btn-view { color: #2196F3; }   /* Синій для перегляду */
    .btn-edit { color: #FF9800; }   /* Помаранчевий для редагування */
    .btn-delete { color: #F44336; } /* Червоний для видалення */
</style>

<main>
    <h2>Список групи</h2>

    <?php if (isset($_GET['deleted'])): ?>
        <p style="color: green; border: 1px solid green; padding: 10px; background: #bfd0b9;">
            <strong>Студента успішно видалено!</strong>
        </p>
    <?php endif; ?>

    <?php if (empty($students)): ?>
        <p><em>Студентів ще не додано.</em></p>
    <?php else: ?>
        <p>Всього студентів: <strong><?= count($students) ?></strong></p>
        
        <table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr bgcolor="#f0f0f0">
                    <th align="left">ПІБ Студента</th>
                    <th align="left">Телефон</th>
                    <th align="center" width="150">Дії</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td>
                        <p href="view_student.php?id=<?= $s['id'] ?>" style="text-decoration: none; color: #333;">
                            <strong><?= htmlspecialchars($s['full_name']) ?></strong>
                        </p>
                    </td>
                    
                    <td>
                        <?= htmlspecialchars($s['phone'] ?? '—') ?> 
                        <?php if(!empty($s['phone'])): ?>
                            <a href="tel:<?= preg_replace('/[^\d+]/', '', $s['phone']) ?>" style="text-decoration: none;">📞</a>
                        <?php endif; ?>
                    </td>
                    
                    <td align="center">
                        <a href="view_student.php?id=<?= $s['id'] ?>" class="action-btn btn-view" title="Переглянути деталі">
                            <i class="fa-solid fa-eye fa-lg"></i>
                        </a>

                        <a href="edit_student.php?id=<?= $s['id'] ?>" class="action-btn btn-edit" title="Редагувати">
                            <i class="fa-solid fa-pen-to-square fa-lg"></i>
                        </a>

                        <form action="logic/delete_student.php" method="POST" style="display:inline;"
                              onsubmit="return confirm('Ви дійсно хочете видалити студента <?= htmlspecialchars($s['full_name'], ENT_QUOTES) ?>? Всі дані (включно з батьками) будуть втрачені!');">
                            <input type="hidden" name="student_id" value="<?= $s['id'] ?>">
                            <button type="submit"
                                    class="action-btn btn-delete"
                                    title="Видалити"
                                    style="background: none; border: none; cursor: pointer;">
                                <i class="fa-solid fa-trash fa-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php require 'blocks/footer.php'; ?>