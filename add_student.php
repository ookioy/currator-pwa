<?php
// Підключення до БД та перевірка авторизації
require 'logic/db.php';
require 'logic/auth.php';

protectPage($pdo);

$pageTitle = "Додати нового студента";
require 'blocks/header.php';
?>

<main>
    <p><a href="main.php">&larr; Назад до списку</a></p>

    <h2>Нова картка студента</h2>

    <form action="logic/insert_student.php" method="POST" id="studentForm">
        
        <fieldset>
            <legend><strong>Дані студента</strong></legend>

            <table border="0" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <td><label for="full_name">ПІБ Студента: <em>*</em></label></td>
                    <td><input type="text" id="full_name" name="full_name" size="40" required></td>
                </tr>
                <tr>
                    <td><label for="phone">Телефон:</label></td>
                    <td><input type="tel" id="phone" name="phone" size="40"></td>
                </tr>
                <tr>
                    <td><label for="birth_date">Дата народження:</label></td>
                    <td><input type="date" id="birth_date" name="birth_date"></td>
                </tr>
                <tr>
                    <td><label for="home_address">Адреса реєстрації:</label></td>
                    <td><input type="text" id="home_address" name="home_address" size="40"></td>
                </tr>
                <tr>
                    <td><label for="actual_address">Фактична адреса:</label></td>
                    <td><input type="text" id="actual_address" name="actual_address" size="40"></td>
                </tr>
                <tr>
                    <td><label for="education">Освіта:</label></td>
                    <td><input type="text" id="education" name="education" size="40"></td>
                </tr>
                <tr>
                    <td><label for="languages">Мови:</label></td>
                    <td><input type="text" id="languages" name="languages" size="40"></td>
                </tr>
                <tr>
                    <td><label for="info_source">Джерело інформації:</label></td>
                    <td><input type="text" id="info_source" name="info_source" size="40"></td>
                </tr>
                <tr>
                    <td><label for="career_goal">Кар'єрна ціль:</label></td>
                    <td><input type="text" id="career_goal" name="career_goal" size="40"></td>
                </tr>
                <tr>
                    <td><label for="programming_languages">Мови програмування:</label></td>
                    <td><input type="text" id="programming_languages" name="programming_languages" size="40"></td>
                </tr>
                <tr>
                    <td valign="top"><label for="activities">Хобі/Інтереси:</label></td>
                    <td><textarea id="activities" name="activities" rows="3" cols="40"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label>
                            <input type="checkbox" name="has_experience" value="1"> Має досвід роботи
                        </label>
                    </td>
                </tr>
            </table>
        </fieldset>

        <br>

        <fieldset>
            <legend><strong>Дані батьків</strong></legend>
            
            <div>
                <h3>Батько</h3>
                <input type="hidden" name="p_type[]" value="father">
                
                <table border="0" cellpadding="5">
                    <tr>
                        <td><label>ПІБ:</label></td>
                        <td><input type="text" name="p_full_name[]" size="50"></td>
                    </tr>
                    <tr>
                        <td><label>Місце роботи:</label></td>
                        <td><input type="text" name="p_work_info[]" size="50"></td>
                    </tr>
                    <tr>
                        <td><label>Телефон:</label></td>
                        <td><input type="tel" name="p_phone[]" size="30"></td>
                    </tr>
                </table>
            </div>

            <div>
                <h3>Мати</h3>
                <input type="hidden" name="p_type[]" value="mother">
                
                <table border="0" cellpadding="5">
                    <tr>
                        <td><label>ПІБ:</label></td>
                        <td><input type="text" name="p_full_name[]" size="50"></td>
                    </tr>
                    <tr>
                        <td><label>Місце роботи:</label></td>
                        <td><input type="text" name="p_work_info[]" size="50"></td>
                    </tr>
                    <tr>
                        <td><label>Телефон:</label></td>
                        <td><input type="tel" name="p_phone[]" size="30"></td>
                    </tr>
                </table>
            </div>
            
        </fieldset>

        <br>
        <p>
            <button type="submit"><strong>Зберегти картку студента</strong></button>
        </p>
    </form>
</main>

<?php require 'blocks/footer.php'; ?>