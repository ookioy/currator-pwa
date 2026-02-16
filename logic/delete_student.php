<?php
require 'db.php';
require 'auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? null;
    
    if ($student_id) {
        try {
            $pdo->beginTransaction();

            // 1. Спочатку видаляємо всіх батьків цього студента
            $sql_parents = "DELETE FROM parents WHERE student_id = ?";
            $stmt_parents = $pdo->prepare($sql_parents);
            $stmt_parents->execute([$student_id]);

            // 2. Потім видаляємо самого студента
            $sql_student = "DELETE FROM students WHERE id = ?";
            $stmt_student = $pdo->prepare($sql_student);
            $stmt_student->execute([$student_id]);

            $pdo->commit();

            // Повертаємося на головну з повідомленням про успіх
            header('Location: ../main.php?deleted=1');
            exit;
            
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            die("Помилка при видаленні студента: " . $e->getMessage());
        }
    }
}

// Якщо щось пішло не так - повертаємо на головну
header('Location: ../main.php');
exit;
