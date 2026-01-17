<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Система куратора' ?></title>
</head>

<body>
    <header>
        <h1><?=$pageTitle?></h1>
        <nav>
            <a href="add_student.php">Додати студента</a> |
            <a href="logout.php">Вийти (logout)</a>
            <br><br>
            <form action="find_student.php" method="get">
                <input type="text" name="full-name" placeholder="Пошук студента">
                <button type="submit">Знайти</button>
            </form>
        </nav>

        <hr>
    </header>