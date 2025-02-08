<?php

//$db_host = "localhost"; // сервер
//$db_user = "injener_esp8266"; // имя пользователя
//$db_pass = "mcALO!wrj7PT"; // пароль
//$db_name = "injener_esp8266"; // название базы данных
//
//// Подключение к базе данных
//$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
//
//// Проверка подключения
//if ($mysqli->connect_error) {
//    die("Ошибка подключения: " . $mysqli->connect_error);
//}

include 'dbConnection.php'; // подключаемся к базе данных

$sensorId = $_GET['sensorId'];//назв.перем=get[ключ после ? или &]

// SQL запрос
$query = "SELECT `date`, `time`, `sensorId`, `temp`,`hum`,`pres` FROM `sensors` WHERE `sensorId` = $sensorId";
$result = $mysqli->query($query); // Выполняем запрос

if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo "{$row['date']} {$row['time']} her= {$row['sensorId']} темп.= {$row['temp']} вл.={$row['hum']} давл.={$row['pres']}<br><br>"; // Выводим данные
    }
} 
else {
    echo "Ошибка выполнения запроса: " . $mysqli->error;
}
$mysqli->close(); // закрываем соединение
