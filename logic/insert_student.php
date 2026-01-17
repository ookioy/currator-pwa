<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // 1. Вставка студента
        $sql = "INSERT INTO students (
            full_name, birth_date, home_address, actual_address, 
            education, languages, programming_languages, activities, 
            has_experience, info_source, career_goal
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['full_name'],
            $_POST['birth_date'] ?: null,
            $_POST['home_address'],
            $_POST['actual_address'],
            $_POST['education'],
            $_POST['languages'],
            $_POST['programming_languages'],
            $_POST['activities'],
            isset($_POST['has_experience']) ? 1 : 0,
            $_POST['info_source'],
            $_POST['career_goal']
        ]);

        $student_id = $pdo->lastInsertId();

        // 2. Вставка батьків
        if (!empty($_POST['p_full_name'])) {
            $sql_parent = "INSERT INTO parents (full_name, work_info, phone, type, student_id) VALUES (?, ?, ?, ?, ?)";
            $stmt_p = $pdo->prepare($sql_parent);

            foreach ($_POST['p_full_name'] as $key => $name) {
                if (trim($name) === "") continue;
                
                $stmt_p->execute([
                    $name,
                    $_POST['p_work_info'][$key],
                    $_POST['p_phone'][$key],
                    $_POST['p_type'][$key],
                    $student_id
                ]);
            }
        }

        $pdo->commit();
        header('Location: main.php?success=1');
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Помилка при збереженні: " . $e->getMessage());
    }
}