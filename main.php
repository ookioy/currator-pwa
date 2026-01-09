<?php
session_start();
if (!isset($_SESSION['logged_in'])) { 
    header('Location: login.php'); 
    exit; 
}

require 'db.php';

$stmt = $pdo->query("SELECT id, full_name FROM students");
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Панель куратора</title>
</head>
<body>
    <h1>Список студентів</h1>
    
    <p>
        <a href="add_student.php">[ + Додати студента ]</a> 
        | 
        <a href="logout.php">[ Вийти ]</a>
    </p>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ПІБ</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['full_name']) ?></td>
                <td>
                    <a href="view_student.php?id=<?= $student['id'] ?>">Переглянути</a> | 
                    <a href="edit_student.php?id=<?= $student['id'] ?>">Редагувати</a> | 
                    <a href="delete_student.php?id=<?= $student['id'] ?>" onclick="return confirm('Видалити?')">Видалити</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>