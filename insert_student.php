<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. ПІБ (Обов'язкове поле)
        $full_name = $_POST['full_name'] ?? 'Невідомий студент';

        // 2. Дата народження (Критично! Якщо порожня — ставимо NULL, а не рядок)
        $birth_date = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;

        // 3. Текстові поля (Використовуємо оператор ?? щоб уникнути Undefined index)
        $home_address = $_POST['home_address'] ?? null;
        $actual_address = $_POST['actual_address'] ?? null;
        
        // УВАГА: назва з твоєї БД - eduacation
        $eduacation = $_POST['eduacation'] ?? null; 
        
        $languages = $_POST['languages'] ?? null;
        $programming_languages = $_POST['programming_languages'] ?? null;
        $activities = $_POST['activities'] ?? null;

        // 4. Чекбокс (has_experience) - TinyInt(1)
        $has_experience = isset($_POST['has_experience']) ? 1 : 0;

        // ПІДГОТОВКА ЗАПИТУ (9 колонок, крім id)
        $sql = "INSERT INTO students (
                    full_name, birth_date, home_address, actual_address, 
                    eduacation, languages, programming_languages, activities, has_experience
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        
        // ВИКОНАННЯ
        $stmt->execute([
            $full_name,
            $birth_date,
            $home_address,
            $actual_address,
            $eduacation,
            $languages,
            $programming_languages,
            $activities,
            $has_experience
        ]);

        // Редирект у разі успіху
        header("Location: main.php?added=success");
        exit;

    } catch (PDOException $e) {
        // Виводимо точну причину помилки бази даних
        die("Помилка бази даних: " . $e->getMessage());
    }
}