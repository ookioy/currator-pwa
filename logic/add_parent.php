<?php
require 'db.php';
require 'auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? null;
    
    if ($student_id) {
        try {
            $sql = "INSERT INTO parents (student_id, full_name, type, work_info, phone) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $student_id,
                $_POST['full_name'],
                $_POST['type'] ?? 'мати',
                $_POST['work_info'] ?: null,
                $_POST['phone'] ?: null
            ]);

            header("Location: ../edit_parents.php?student_id=$student_id&updated=1");
            exit;
            
        } catch (Exception $e) {
            die("Помилка при додаванні батька: " . $e->getMessage());
        }
    }
}

header('Location: ../main.php');
exit;