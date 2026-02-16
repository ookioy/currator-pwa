<?php
// Підключення до БД та перевірка авторизації
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$pageTitle = "Додати нового студента";
require 'blocks/header.php';
?>

<main>
    <!-- Навігація -->
    <p><a href="main.php">&larr; Назад до списку</a></p>

    <h2>Нова картка студента</h2>

    <!-- Форма додавання студента -->
    <form action="logic/insert_student.php" method="POST" id="studentForm">
        
        <!-- Блок: Дані студента -->
        <fieldset>
            <legend><strong>Дані студента</strong></legend>

            <p>
                <label for="full_name">ПІБ Студента: <em>(обов'язкове)</em></label><br>
                <input type="text" id="full_name" name="full_name" size="50" required>
            </p>

            <p>
                <label for="phone">Телефон:</label><br>
                <input type="tel" id="phone" name="phone" size="30">
            </p>

            <p>
                <label for="birth_date">Дата народження:</label><br>
                <input type="date" id="birth_date" name="birth_date">
            </p>

            <p>
                <label for="home_address">Адреса реєстрації:</label><br>
                <input type="text" id="home_address" name="home_address" size="60">
            </p>

            <p>
                <label for="actual_address">Фактична адреса:</label><br>
                <input type="text" id="actual_address" name="actual_address" size="60">
            </p>

            <p>
                <label for="education">Освіта:</label><br>
                <input type="text" id="education" name="education" size="50">
            </p>

            <p>
                <label for="languages">Мови:</label><br>
                <input type="text" id="languages" name="languages" size="40" placeholder="Наприклад: Українська, Англійська">
            </p>

            <p>
                <label for="info_source">Джерело інформації:</label><br>
                <input type="text" id="info_source" name="info_source" size="40" placeholder="Звідки дізнався про курс">
            </p>

            <p>
                <label for="career_goal">Кар'єрна ціль:</label><br>
                <input type="text" id="career_goal" name="career_goal" size="50">
            </p>

            <p>
                <label for="programming_languages">Мови програмування:</label><br>
                <input type="text" id="programming_languages" name="programming_languages" size="50" placeholder="Наприклад: Python, JavaScript">
            </p>

            <p>
                <label for="activities">Хобі/Інтереси:</label><br>
                <textarea id="activities" name="activities" rows="3" cols="60"></textarea>
            </p>

            <p>
                <label>
                    <input type="checkbox" name="has_experience" value="1">
                    Має досвід роботи
                </label>
            </p>
        </fieldset>

        <br>

        <!-- Блок: Дані батьків -->
        <fieldset>
            <legend><strong>Дані батьків/опікунів</strong></legend>
            
            <!-- Контейнер для батьків (динамічно додаються) -->
            <div id="parents-container">
                <!-- Перший батько (обов'язковий) -->
                <fieldset>
                    <legend>Батько/Мати/Опікун #1</legend>
                    
                    <p>
                        <label for="p_full_name_0">ПІБ: <em>(обов'язкове)</em></label><br>
                        <input type="text" id="p_full_name_0" name="p_full_name[]" size="50" required>
                    </p>
                    
                    <p>
                        <label for="p_type_0">Тип:</label><br>
                        <select id="p_type_0" name="p_type[]">
                            <option value="mother">Мати</option>
                            <option value="father">Батько</option>
                        </select>
                    </p>
                    
                    <p>
                        <label for="p_work_info_0">Місце роботи:</label><br>
                        <input type="text" id="p_work_info_0" name="p_work_info[]" size="50">
                    </p>
                    
                    <p>
                        <label for="p_phone_0">Телефон:</label><br>
                        <input type="tel" id="p_phone_0" name="p_phone[]" size="30">
                    </p>
                </fieldset>
            </div>
            
            <br>
            <button type="button" id="add-parent-btn">+ Додати ще одного батька/опікуна</button>
        </fieldset>

        <br>
        <p>
            <button type="submit"><strong>Зберегти студента та батьків</strong></button>
        </p>
    </form>
</main>

<!-- JavaScript для динамічного додавання батьків -->
<script>
let parentCount = 1;

document.getElementById('add-parent-btn').addEventListener('click', function() {
    const container = document.getElementById('parents-container');
    const newEntry = document.createElement('fieldset');
    
    newEntry.innerHTML = `
        <legend>Батько/Мати/Опікун #${parentCount + 1}</legend>
        
        <p>
            <label for="p_full_name_${parentCount}">ПІБ: <em>(обов'язкове)</em></label><br>
            <input type="text" id="p_full_name_${parentCount}" name="p_full_name[]" size="50" required>
        </p>
        
        <p>
            <label for="p_type_${parentCount}">Тип:</label><br>
            <select id="p_type_${parentCount}" name="p_type[]">
                <option value="mother">Мати</option>
                <option value="father">Батько</option>
            </select>
        </p>
        
        <p>
            <label for="p_work_info_${parentCount}">Місце роботи:</label><br>
            <input type="text" id="p_work_info_${parentCount}" name="p_work_info[]" size="50">
        </p>
        
        <p>
            <label for="p_phone_${parentCount}">Телефон:</label><br>
            <input type="tel" id="p_phone_${parentCount}" name="p_phone[]" size="30">
        </p>
        
        <p>
            <button type="button" onclick="this.parentElement.parentElement.remove()">Видалити цього батька/опікуна</button>
        </p>
    `;
    
    container.appendChild(newEntry);
    parentCount++;
});
</script>

<?php require 'blocks/footer.php'; ?>
