<?php
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$pageTitle = "Додати нового студента";
require 'blocks/header.php';
?>

<main>
    <a href="main.php">Назад до списку</a>
    <h1>Нова картка студента</h1>

    <form action="insert_student.php" method="POST" id="studentForm">
        <div>
            <h3>Дані студента</h3>
            <input type="text" name="full_name" placeholder="ПІБ Студента" required>
            <input type="date" name="birth_date" required>
            <input type="text" name="home_address" placeholder="Адреса реєстрації" required>
            <input type="text" name="actual_address" placeholder="Фактична адреса">
            <input type="text" name="education" placeholder="Освіта">
            <input type="text" name="languages" placeholder="Мови">
            <input type="text" name="programming_languages" placeholder="Мови програмування">
            <textarea name="activities" placeholder="Хобі/Інтереси"></textarea>
            
            <label>
                <input type="checkbox" name="has_experience" value="1"> Має досвід роботи
            </label>
            
            <input type="text" name="info_source" placeholder="Звідки дізналися">
            <input type="text" name="career_goal" placeholder="Кар'єрна ціль">
        </div>

        <hr>

        <div>
            <h3>Дані батьків</h3>
            <div id="parents-container">
                <div class="parent-entry">
                    <input type="text" name="p_full_name[]" placeholder="ПІБ" required>
                    <input type="text" name="p_work_info[]" placeholder="Місце роботи">
                    <input type="text" name="p_phone[]" placeholder="Телефон" required>
                    <select name="p_type[]">
                        <option value="мати">Мати</option>
                        <option value="батько">Батько</option>
                        <option value="опікун">Опікун</option>
                    </select>
                </div>
            </div>
            <button type="button" id="add-parent-btn">+ Додати ще одного</button>
        </div>

        <hr>
        <button type="submit">Зберегти студента та батьків</button>
    </form>
</main>

<script>
document.getElementById('add-parent-btn').addEventListener('click', function() {
    const container = document.getElementById('parents-container');
    const newEntry = document.createElement('div');
    newEntry.className = 'parent-entry';
    
    // Прибрано <hr> з початку шаблону
    newEntry.innerHTML = `
        <input type="text" name="p_full_name[]" placeholder="ПІБ" required>
        <input type="text" name="p_work_info[]" placeholder="Місце роботи">
        <input type="text" name="p_phone[]" placeholder="Телефон" required>
        <select name="p_type[]">
            <option value="мати">Мати</option>
            <option value="батько">Батько</option>
            <option value="опікун">Опікун</option>
        </select>
        <button type="button" onclick="this.parentElement.remove()">Видалити</button>
    `;
    container.appendChild(newEntry);
});
</script>

<?php require 'blocks/footer.php'; ?>