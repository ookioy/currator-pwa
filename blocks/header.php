<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Система куратора' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Шапка сайту -->
    <header>
        <h1><?= $pageTitle ?></h1>
        
        <!-- Головна навігація -->
        <nav>
            <a href="add_student.php">Додати студента</a> |
            <a href="change_password.php">Поміняти пароль</a> |
            <a href="logic/logout.php">Вийти</a>
        </nav>
        
        <br>
        
        <!-- Форма пошуку студентів -->
        <form action="find_student.php" method="get" role="search">
            <label for="search-input">Пошук студента:</label>
            <input type="text" 
                   id="search-input" 
                   name="full-name" 
                   placeholder="Введіть ПІБ студента"
                   size="40">
            <button type="submit">Знайти</button>
        </form>
        
        <hr>
    </header>
