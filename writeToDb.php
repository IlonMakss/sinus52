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

$temp = $_GET['temp'];
$hum = $_GET['hum'];
$pres = $_GET['pres'];

$sensorId = $_GET['sensorId'];
$date = date('Y-m-d');
$time = date('H:i:s');

// SQL запрос
if($temp!=0){
$query = "INSERT INTO sensors (date, time, sensorId, temp, hum, pres) VALUES ('$date', '$time', '$sensorId', '$temp','$hum','$pres')";
$result = $mysqli->query($query); // Выполняем запрос
}
if ($result) {
    echo "Запись успешно добавлена!";
} else {
    echo "Ошибка: " . $mysqli->error;
}

$mysqli->close(); // закрываем соединение