<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

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
    <a href="view_student.php?id=<?= $student_id ?>">← Назад до профілю</a>
    <h1>Редагування батьків/опікунів</h1>

    <?php if (isset($_GET['updated'])): ?>
        <p style="color: green;"><b>✅ Зміни збережено!</b></p>
    <?php endif; ?>

    <h3>Поточні батьки/опікуни</h3>

    <?php if (empty($parents)): ?>
        <p>Батьків ще не додано.</p>
    <?php else: ?>
        <?php foreach ($parents as $parent): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <form action="logic/update_parent.php" method="POST" style="display: inline;">
                    <input type="hidden" name="parent_id" value="<?= $parent['id'] ?>">
                    <input type="hidden" name="student_id" value="<?= $student_id ?>">
                    
                    <p>
                        <label>ПІБ:</label><br>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($parent['full_name']) ?>" required>
                    </p>
                    
                    <p>
                        <label>Тип:</label><br>
                        <select name="type">
                            <option value="мати" <?= $parent['type'] === 'мати' ? 'selected' : '' ?>>Мати</option>
                            <option value="батько" <?= $parent['type'] === 'батько' ? 'selected' : '' ?>>Батько</option>
                            <option value="опікун" <?= $parent['type'] === 'опікун' ? 'selected' : '' ?>>Опікун</option>
                        </select>
                    </p>
                    
                    <p>
                        <label>Місце роботи:</label><br>
                        <input type="text" name="work_info" value="<?= htmlspecialchars($parent['work_info'] ?? '') ?>">
                    </p>
                    
                    <p>
                        <label>Телефон:</label><br>
                        <input type="text" name="phone" value="<?= htmlspecialchars($parent['phone'] ?? '') ?>">
                    </p>
                    
                    <button type="submit">Оновити</button>
                </form>
                
                <form action="logic/delete_parent.php" method="POST" style="display: inline;" onsubmit="return confirm('Видалити цього батька/опікуна?');">
                    <input type="hidden" name="parent_id" value="<?= $parent['id'] ?>">
                    <input type="hidden" name="student_id" value="<?= $student_id ?>">
                    <button type="submit">Видалити</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <hr>

    <h3>Додати нового батька/опікуна</h3>
    <form action="logic/add_parent.php" method="POST">
        <input type="hidden" name="student_id" value="<?= $student_id ?>">
        
        <p>
            <label>ПІБ:</label><br>
            <input type="text" name="full_name" required>
        </p>
        
        <p>
            <label>Тип:</label><br>
            <select name="type">
                <option value="мати">Мати</option>
                <option value="батько">Батько</option>
                <option value="опікун">Опікун</option>
            </select>
        </p>
        
        <p>
            <label>Місце роботи:</label><br>
            <input type="text" name="work_info">
        </p>
        
        <p>
            <label>Телефон:</label><br>
            <input type="text" name="phone">
        </p>
        
        <button type="submit">Додати</button>
    </form>
</main>

<?php require 'blocks/footer.php'; ?>