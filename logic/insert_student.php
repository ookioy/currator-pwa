<?php
require 'db.php';
require 'auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // 1. Вставка студента - всі поля з таблиці students
        $sql = "INSERT INTO students (
            full_name, phone, birth_date, home_address, actual_address, 
            education, languages, info_source, career_goal, 
            programming_languages, activities, has_experience
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

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
            isset($_POST['has_experience']) ? 1 : 0
        ]);

        $student_id = $pdo->lastInsertId();

        // 2. Вставка батьків - всі поля з таблиці parents
        if (!empty($_POST['p_full_name'])) {
            $sql_parent = "INSERT INTO parents (student_id, full_name, type, work_info, phone) VALUES (?, ?, ?, ?, ?)";
            $stmt_p = $pdo->prepare($sql_parent);

            foreach ($_POST['p_full_name'] as $key => $name) {
                if (trim($name) === "") continue;
                
                $stmt_p->execute([
                    $student_id,
                    $name,
                    $_POST['p_type'][$key] ?? 'мати',
                    $_POST['p_work_info'][$key] ?? null,
                    $_POST['p_phone'][$key] ?? null
                ]);
            }
        }

        $pdo->commit();
        header('Location: ../main.php?success=1');
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Помилка при збереженні: " . $e->getMessage());
    }
} else {
    header('Location: ../main.php');
    exit;
}