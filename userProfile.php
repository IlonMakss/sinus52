<?php

session_start();

require_once __DIR__ . '/dbUsersConnect.php';

$connect = getDB();

$idUser = $_SESSION['user']['id'];

if ($idUser == '') {
    header("Location: /");
}

$sql = "SELECT * FROM `users` WHERE `id` = ('$idUser')";

$result = mysqli_query($connect, $sql);
$result = mysqli_fetch_all($result);

$login;
$sensorId;
foreach($result as $item) {
    $login = $item[1];
}
foreach($result as $item) {
    $sensorId = $item[3];
}


?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <title>Личный кабинет</title>
    <style>
        /* Основные стили */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0; /* Исправлено: было 40 */
        }

        main {
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 40px;
            color: #333;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 40px;
        }

        a {
            padding: 12px 24px;
            font-size: 16px;
            color: white;
            background-color: #dc3545;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block; /* Чтобы кнопки были в одну линию */
            margin: 5px; /* Отступ между кнопками */
        }

        a:hover {
            background-color: #c82333;
        }

        .buttons {
            margin-bottom: 20px; /* Отступ между блоками кнопок */
        }

        .logout-button {
            margin-top: 20px; /* Дополнительный отступ для кнопки "Выход" */
        }
    </style>
</head>
<body>
    <main>
        <h2>Личный кабинет</h2>
        <p>Добро пожаловать! <?= $login ?></p>
        <p>ID вашей метеостанции: <?= $sensorId ?></p>
        
        <!-- Блок кнопок для графиков -->
        <div class="buttons">
            <a href="graphTemp.php" class="temperature">График температуры</a>
            <a href="graphHum.php" class="humidity">График влажности</a>
        </div>

        <!-- Блок кнопки "Выход" -->
        <div class="buttons logout-button">
            <a href="logout.php">Выход</a>
        </div>
    </main>
</body>
</html>