<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$pageTitle = "Додати нового студента";
require 'blocks/header.php';
?>

<main>
    <a href="main.php">← Назад до списку</a>
    <h1>Нова картка студента</h1>
    <hr>

    <form action="insert_student.php" method="POST">
        
        <p>
            <label><b>ПІБ (повністю):</b></label><br>
            <input type="text" name="full_name" required">
        </p>

        <p>
            <label><b>Дата народження:</b></label><br>
            <input type="date" name="birth_date">
        </p>

        <p>
            <label><b>Адреса за пропискою:</b></label><br>
            <input type="text" name="home_address">
        </p>

        <p>
            <label><b>Фактична адреса проживання:</b></label><br>
            <input type="text" name="actual_address">
        </p>

        <p>
            <label><b>Освіта (попередня):</b></label><br>
            <input type="text" name="eduacation" placeholder="Школа №... або коледж">
        </p>

        <p>
            <label><b>Іноземні мови:</b></label><br>
            <input type="text" name="languages" placeholder="Англійська (B1) тощо">
        </p>

        <p>
            <label><b>Мови програмування (базові знання):</b></label><br>
            <input type="text" name="programming_languages">
        </p>

        <p>
            <label><b>Захоплення / Спортивні секції:</b></label><br>
            <textarea name="activities" rows="3"></textarea>
        </p>

        <p>
            <label>
                <input type="checkbox" name="has_experience" value="1">
                <b>Має досвід роботи?</b>
            </label>
        </p>

        <br>
        <button type="submit">
            Створити картку
        </button>
    </form>
</main>

<?php require 'blocks/footer.php'; ?>