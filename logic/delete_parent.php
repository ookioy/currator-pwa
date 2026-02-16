<?php
require 'db.php';
require 'auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parent_id = $_POST['parent_id'] ?? null;
    $student_id = $_POST['student_id'] ?? null;
    
    if ($parent_id && $student_id) {
        try {
            $sql = "DELETE FROM parents WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$parent_id]);

            header("Location: ../edit_parents.php?student_id=$student_id&updated=1");
            exit;
            
        } catch (Exception $e) {
            die("Помилка при видаленні батька: " . $e->getMessage());
        }
    }
}

header('Location: ../main.php');
exit;
