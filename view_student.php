<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: main.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) { die("Студента не знайдено!"); }

$pageTitle = "Профіль: " . htmlspecialchars($student['full_name']);
require 'blocks/header.php';
?>

<main>
    <a href="main.php">← До списку</a>
    <h1>Перегляд та редагування</h1>

    <?php if (isset($_GET['updated'])): ?>
        <p style="color: green;"><b>✅ Зміни збережено!</b></p>
    <?php endif; ?>

    <form action="update_student.php" method="POST" onsubmit="return confirm('Зберегти зміни?');">
        
        <input type="hidden" name="id" value="<?= $student['id'] ?>">

        <p>
            <label>ПІБ:</label><br>
            <input type="text" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>" required>
        </p>

        <p>
            <label>Телефон:</label><br>
            <input type="text" name="phone" value="<?= htmlspecialchars($student['phone']) ?>">
        </p>
        
        <p>
            <label>Нотатки:</label><br>
            <textarea name="additional_info" rows="5"><?= htmlspecialchars($student['additional_info']) ?></textarea>
        </p>

        <button type="submit">Зберегти</button>
    </form>
</main>

<?php require 'blocks/footer.php'; ?>