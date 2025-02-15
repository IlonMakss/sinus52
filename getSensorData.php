<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Данные с датчиков</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .sensor-table {
            width: 90%;
            max-width: 1000px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .sensor-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .sensor-table th, .sensor-table td {
            padding: 15px;
            text-align: center;
        }

        .sensor-table th {
            background-color: #28a745;
            color: white;
            font-size: 18px;
        }

        .sensor-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .sensor-table tr:hover {
            background-color: #f1f1f1;
        }

        .sensor-table td {
            font-size: 16px;
            color: #333;
        }

        .sensor-table caption {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border-radius: 10px 10px 0 0;
        }
    </style>
</head>
<body>
    <div class="sensor-table">
        <table>
            <caption>Данные с датчиков</caption>
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Время</th>
                    <th>ID датчика</th>
                    <th>Температура (°C)</th>
                    <th>Влажность (%)</th>
                    <th>Давление (hPa)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'dbConnection.php'; // подключаемся к базе данных
                // Подключение к базе данных
                $sensorId = $_GET['sensorId'];//назв.перем=get[ключ после ? или &]

                // SQL запрос
                $query = "SELECT `date`, `time`, `sensorId`, `temp`,`hum`,`pres` FROM `sensors` WHERE `sensorId` = $sensorId";
                $result = $mysqli->query($query); // Выполняем запрос
                if ($result) {
                    // Выводим данные в таблицу
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sensorId']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['temp']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['hum']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pres']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Ошибка выполнения запроса: " . $mysqli->error . "</td></tr>";
                    echo "Ошибка выполнения запроса: " . $mysqli->error;
                    }
                    $mysqli->close(); 
                

                // Закрываем соединение
                $mysqli->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
/*
include 'dbConnection.php'; // подключаемся к базе данных
$sensorId = $_GET['sensorId'];//назв.перем=get[ключ после ? или &]

// SQL запрос
$query = "SELECT `date`, `time`, `sensorId`, `temp`,`hum`,`pres` FROM `sensors` WHERE `sensorId` = $sensorId";
$result = $mysqli->query($query); // Выполняем запрос
if ($result) {
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        $acc = $row['date'];
        echo "<td>$acc</td>";
        
        echo "</tr>"; // Выводим данные
    }
} 
else {
    echo "Ошибка выполнения запроса: " . $mysqli->error;
}
$mysqli->close(); // закрываем соединение*/


   
