<?php
// Підключення до БД та перевірка авторизації
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

// Отримання ID студента
$student_id = $_GET['student_id'] ?? null;
if (!$student_id) {
    header('Location: main.php');
    exit;
}

// Отримуємо студента
$stmt = $pdo->prepare("SELECT full_name FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    die("Студента не знайдено!");
}

// Отримуємо батьків
$stmt_parents = $pdo->prepare("SELECT * FROM parents WHERE student_id = ? ORDER BY type");
$stmt_parents->execute([$student_id]);
$parents = $stmt_parents->fetchAll();

$pageTitle = "Редагування батьків: " . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <!-- Навігація -->
    <p><a href="view_student.php?id=<?= $student_id ?>">&larr; Назад до профілю</a></p>

    <h2>Редагування батьків/опікунів</h2>

    <!-- Повідомлення про успіх -->
    <?php if (isset($_GET['updated'])): ?>
        <p><strong>Зміни збережено!</strong></p>
    <?php endif; ?>

    <!-- Список поточних батьків -->
    <fieldset>
        <legend><strong>Поточні батьки/опікуни</strong></legend>

        <?php if (empty($parents)): ?>
            <p><em>Батьків ще не додано.</em></p>
        <?php else: ?>
            <?php foreach ($parents as $index => $parent): ?>
                <!-- Картка батька -->
                <fieldset>
                    <legend>Батько/Мати/Опікун #<?= $index + 1 ?></legend>

                    <!-- Форма оновлення батька -->
                    <form action="logic/update_parent.php" method="POST">
                        <input type="hidden" name="parent_id" value="<?= $parent['id'] ?>">
                        <input type="hidden" name="student_id" value="<?= $student_id ?>">

                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <td width="25%"><label for="full_name_<?= $parent['id'] ?>">ПІБ:</label></td>
                                <td>
                                    <input type="text"
                                           id="full_name_<?= $parent['id'] ?>"
                                           name="full_name"
                                           value="<?= htmlspecialchars($parent['full_name']) ?>"
                                           size="50"
                                           required>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="type_<?= $parent['id'] ?>">Тип:</label></td>
                                <td>
                                    <select id="type_<?= $parent['id'] ?>" name="type">
                                        <option value="mother" <?= $parent['type'] === 'mother' ? 'selected' : '' ?>>Мати</option>
                                        <option value="father" <?= $parent['type'] === 'father' ? 'selected' : '' ?>>Батько</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td><label for="work_info_<?= $parent['id'] ?>">Місце роботи:</label></td>
                                <td>
                                    <input type="text"
                                           id="work_info_<?= $parent['id'] ?>"
                                           name="work_info"
                                           value="<?= htmlspecialchars($parent['work_info'] ?? '') ?>"
                                           size="50">
                                </td>
                            </tr>

                            <tr>
                                <td><label for="phone_<?= $parent['id'] ?>">Телефон:</label></td>
                                <td>
                                    <input type="tel"
                                           id="phone_<?= $parent['id'] ?>"
                                           name="phone"
                                           value="<?= htmlspecialchars($parent['phone'] ?? '') ?>"
                                           size="30">
                                </td>
                            </tr>
                        </table>

                        <p>
                            <button type="submit">Оновити</button>
                        </p>
                    </form>

                    <!-- Форма видалення батька -->
                    <form action="logic/delete_parent.php" method="POST" onsubmit="return confirm('Видалити цього батька/опікуна?');">
                        <input type="hidden" name="parent_id" value="<?= $parent['id'] ?>">
                        <input type="hidden" name="student_id" value="<?= $student_id ?>">
                        <button type="submit">Видалити</button>
                    </form>
                </fieldset>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>
    </fieldset>

    <br>

    <!-- Форма додавання нового батька -->
    <fieldset>
        <legend><strong>Додати нового батька/опікуна</strong></legend>

        <form action="logic/add_parent.php" method="POST">
            <input type="hidden" name="student_id" value="<?= $student_id ?>">

            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td width="25%"><label for="new_full_name">ПІБ:</label></td>
                    <td><input type="text" id="new_full_name" name="full_name" size="50" required></td>
                </tr>

                <tr>
                    <td><label for="new_type">Тип:</label></td>
                    <td>
                        <select id="new_type" name="type">
                            <option value="mother">Мати</option>
                            <option value="father">Батько</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><label for="new_work_info">Місце роботи:</label></td>
                    <td><input type="text" id="new_work_info" name="work_info" size="50"></td>
                </tr>

                <tr>
                    <td><label for="new_phone">Телефон:</label></td>
                    <td><input type="tel" id="new_phone" name="phone" size="30"></td>
                </tr>
            </table>

            <p>
                <button type="submit"><strong>Додати</strong></button>
            </p>
        </form>
    </fieldset>
</main>

<?php require 'blocks/footer.php'; ?>
