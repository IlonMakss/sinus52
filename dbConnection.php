<?php

$db_host = "localhost"; // сервер
$db_user = "macsimxn_bd"; // имя пользователя
$db_pass = "Maxmax1"; // пароль
$db_name = "macsimxn_bd"; // название базы данных

// Подключение к базе данных
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Проверка подключения
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}
