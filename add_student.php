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

    <form action="logic/insert_student.php" method="POST" id="studentForm">
        <div>
            <h3>Дані студента</h3>
            
            <p>
                <label>ПІБ Студента:</label><br>
                <input type="text" name="full_name" required>
            </p>

            <p>
                <label>Телефон:</label><br>
                <input type="text" name="phone">
            </p>

            <p>
                <label>Дата народження:</label><br>
                <input type="date" name="birth_date">
            </p>

            <p>
                <label>Адреса реєстрації:</label><br>
                <input type="text" name="home_address">
            </p>

            <p>
                <label>Фактична адреса:</label><br>
                <input type="text" name="actual_address">
            </p>

            <p>
                <label>Освіта:</label><br>
                <input type="text" name="education">
            </p>

            <p>
                <label>Мови:</label><br>
                <input type="text" name="languages">
            </p>

            <p>
                <label>Джерело інформації:</label><br>
                <input type="text" name="info_source">
            </p>

            <p>
                <label>Кар'єрна ціль:</label><br>
                <input type="text" name="career_goal">
            </p>

            <p>
                <label>Мови програмування:</label><br>
                <input type="text" name="programming_languages">
            </p>

            <p>
                <label>Хобі/Інтереси:</label><br>
                <textarea name="activities" rows="3"></textarea>
            </p>
            
            <p>
                <label>
                    <input type="checkbox" name="has_experience" value="1"> Має досвід роботи
                </label>
            </p>
        </div>

        <hr>

        <div>
            <h3>Дані батьків</h3>
            <div id="parents-container">
                <div class="parent-entry">
                    <p>
                        <label>ПІБ:</label><br>
                        <input type="text" name="p_full_name[]" required>
                    </p>
                    <p>
                        <label>Тип (мати/батько/опікун):</label><br>
                        <select name="p_type[]">
                            <option value="мати">Мати</option>
                            <option value="батько">Батько</option>
                            <option value="опікун">Опікун</option>
                        </select>
                    </p>
                    <p>
                        <label>Місце роботи:</label><br>
                        <input type="text" name="p_work_info[]">
                    </p>
                    <p>
                        <label>Телефон:</label><br>
                        <input type="text" name="p_phone[]">
                    </p>
                </div>
            </div>
            <button type="button" id="add-parent-btn">+ Додати ще одного батька/опікуна</button>
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
    
    newEntry.innerHTML = `
        <hr>
        <p>
            <label>ПІБ:</label><br>
            <input type="text" name="p_full_name[]" required>
        </p>
        <p>
            <label>Тип (мати/батько/опікун):</label><br>
            <select name="p_type[]">
                <option value="мати">Мати</option>
                <option value="батько">Батько</option>
                <option value="опікун">Опікун</option>
            </select>
        </p>
        <p>
            <label>Місце роботи:</label><br>
            <input type="text" name="p_work_info[]">
        </p>
        <p>
            <label>Телефон:</label><br>
            <input type="text" name="p_phone[]" required>
        </p>
        <button type="button" onclick="this.parentElement.remove()">Видалити цього батька/опікуна</button>
    `;
    container.appendChild(newEntry);
});
</script>

<?php require 'blocks/footer.php'; ?>