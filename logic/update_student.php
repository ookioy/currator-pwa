<?php
require 'db.php';
require 'auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    
    if ($id) {
        try {
            // Оновлюємо всі поля з таблиці students
            $sql = "UPDATE students 
                    SET full_name = ?, 
                        phone = ?, 
                        birth_date = ?,
                        home_address = ?, 
                        actual_address = ?,
                        education = ?,
                        languages = ?,
                        info_source = ?,
                        career_goal = ?,
                        programming_languages = ?,
                        activities = ?,
                        has_experience = ?
                    WHERE id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['full_name'],
                $_POST['phone'] ?: null,
                $_POST['birth_date'] ?: null,
                $_POST['home_address'] ?: null,
                $_POST['actual_address'] ?: null,
                $_POST['education'] ?: null,
                $_POST['languages'] ?: null,
                $_POST['info_source'] ?: null,
                $_POST['career_goal'] ?: null,
                $_POST['programming_languages'] ?: null,
                $_POST['activities'] ?: null,
                isset($_POST['has_experience']) ? 1 : 0,
                $id
            ]);

            header("Location: ../view_student.php?id=$id&updated=1");
            exit;
            
        } catch (Exception $e) {
            die("Помилка при оновленні: " . $e->getMessage());
        }
    }
}

header('Location: ../main.php');
exit;