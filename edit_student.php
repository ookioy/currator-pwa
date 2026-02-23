<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: main.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) die("Студента не знайдено!");

$stmt_parents = $pdo->prepare("SELECT * FROM parents WHERE student_id = ?");
$stmt_parents->execute([$id]);
$parents_list = $stmt_parents->fetchAll();

$father = ['id' => '', 'full_name' => '', 'work_info' => '', 'phone' => ''];
$mother = ['id' => '', 'full_name' => '', 'work_info' => '', 'phone' => ''];

foreach ($parents_list as $p) {
    if ($p['type'] === 'father') $father = $p;
    if ($p['type'] === 'mother') $mother = $p;
}

$pageTitle = "Редагування: " . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <p><a href="main.php">&larr; Назад до списку</a></p>

    <h2>Редагування картки студента</h2>

    <?php if (isset($_GET['updated'])): ?>
        <p style="color: green; border: 1px solid green; padding: 10px; background: #eaffea;">
            <strong>✅ Дані успішно оновлено!</strong>
        </p>
    <?php endif; ?>

    <!-- onsubmit removed — modal in footer handles confirmation -->
    <form action="logic/update_student.php" method="POST">
        <input type="hidden" name="id" value="<?= $student['id'] ?>">

        <fieldset>
            <legend><strong>Дані студента</strong></legend>
            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td><label for="full_name">ПІБ Студента: <em>*</em></label></td>
                    <td><input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" size="40" required></td>
                </tr>
                <tr>
                    <td><label for="phone">Телефон:</label></td>
                    <td><input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="birth_date">Дата народження:</label></td>
                    <td><input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($student['birth_date'] ?? '') ?>"></td>
                </tr>
                <tr>
                    <td><label for="home_address">Адреса реєстрації:</label></td>
                    <td><input type="text" id="home_address" name="home_address" value="<?= htmlspecialchars($student['home_address'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="actual_address">Фактична адреса:</label></td>
                    <td><input type="text" id="actual_address" name="actual_address" value="<?= htmlspecialchars($student['actual_address'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="education">Освіта:</label></td>
                    <td><input type="text" id="education" name="education" value="<?= htmlspecialchars($student['education'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="languages">Мови:</label></td>
                    <td><input type="text" id="languages" name="languages" value="<?= htmlspecialchars($student['languages'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="info_source">Джерело інформації:</label></td>
                    <td><input type="text" id="info_source" name="info_source" value="<?= htmlspecialchars($student['info_source'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="career_goal">Кар'єрна ціль:</label></td>
                    <td><input type="text" id="career_goal" name="career_goal" value="<?= htmlspecialchars($student['career_goal'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td><label for="programming_languages">Мови програмування:</label></td>
                    <td><input type="text" id="programming_languages" name="programming_languages" value="<?= htmlspecialchars($student['programming_languages'] ?? '') ?>" size="40"></td>
                </tr>
                <tr>
                    <td valign="top"><label for="activities">Хобі/Інтереси:</label></td>
                    <td><textarea id="activities" name="activities" rows="3" cols="40"><?= htmlspecialchars($student['activities'] ?? '') ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label>
                            <input type="checkbox" name="has_experience" value="1" <?= $student['has_experience'] ? 'checked' : '' ?>>
                            Має досвід роботи
                        </label>
                    </td>
                </tr>
            </table>
        </fieldset>

        <br>

        <fieldset>
            <legend><strong>Дані батьків</strong></legend>

            <div>
                <h3>Батько</h3>
                <input type="hidden" name="parents[father][id]" value="<?= $father['id'] ?>">
                <input type="hidden" name="parents[father][type]" value="father">
                <table border="0" cellpadding="5">
                    <tr>
                        <td><label>ПІБ:</label></td>
                        <td><input type="text" name="parents[father][full_name]" value="<?= htmlspecialchars($father['full_name']) ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td><label>Місце роботи:</label></td>
                        <td><input type="text" name="parents[father][work_info]" value="<?= htmlspecialchars($father['work_info'] ?? '') ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td><label>Телефон:</label></td>
                        <td><input type="tel" name="parents[father][phone]" value="<?= htmlspecialchars($father['phone'] ?? '') ?>" size="30"></td>
                    </tr>
                </table>
            </div>

            <div>
                <h3>Мати</h3>
                <input type="hidden" name="parents[mother][id]" value="<?= $mother['id'] ?>">
                <input type="hidden" name="parents[mother][type]" value="mother">
                <table border="0" cellpadding="5">
                    <tr>
                        <td><label>ПІБ:</label></td>
                        <td><input type="text" name="parents[mother][full_name]" value="<?= htmlspecialchars($mother['full_name']) ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td><label>Місце роботи:</label></td>
                        <td><input type="text" name="parents[mother][work_info]" value="<?= htmlspecialchars($mother['work_info'] ?? '') ?>" size="50"></td>
                    </tr>
                    <tr>
                        <td><label>Телефон:</label></td>
                        <td><input type="tel" name="parents[mother][phone]" value="<?= htmlspecialchars($mother['phone'] ?? '') ?>" size="30"></td>
                    </tr>
                </table>
            </div>
        </fieldset>

        <br>
        <p>
            <button type="submit"><strong>Зберегти зміни</strong></button>
        </p>
    </form>
</main>

<?php require 'blocks/footer.php'; ?>