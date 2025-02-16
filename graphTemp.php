<?php
session_start();

//взятие id датчика из бд
require_once __DIR__ . '/dbUsersConnect.php';
$connect = getDB();

$idUser = $_SESSION['user']['id'];
if ($idUser == '') {
    header("Location: /");
}
$sql = "SELECT * FROM `users` WHERE `id` = ('$idUser')";

$result = mysqli_query($connect, $sql);
$result = mysqli_fetch_all($result);
foreach($result as $item) {
    $sensorId = $item[3];
}
include 'dbConnection.php'; // подключаемся к базе данных
// SQL запрос
$query = "SELECT `date`, `time`, `temp` FROM `sensors` WHERE `sensorId` = $sensorId";
$result = $mysqli->query($query); // Выполняем запрос
if ($result) {
    // Выводим данные в таблицу
    while ($row = $result->fetch_assoc()) {
        $arr_data[]= $row['date'];
        $time[]= $row['time'];
        $temp[]= $row['temp'];
       
    }
$temp_json = json_encode($temp);
$time_json = json_encode($time);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>График температуры</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #myChart {
            width: 1200px; /* Увеличенная ширина */
            height: 600px; /* Увеличенная высота */
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <h1>График температуры</h1>
    <canvas id="myChart"></canvas>

    <script>
        // Пример данных
        const temp = <?php echo $temp_json; ?>;
        const time = <?php echo $time_json; ?>;

        // Создаем график
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: time,
                datasets: [{
                    label: 'Температура (°C)',
                    data: temp,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false, // Отключаем автоматическое масштабирование
                maintainAspectRatio: false, // Отключаем сохранение пропорций
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y + ' °C';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

