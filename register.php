<?php

require_once __DIR__ . '/dbUsersConnect.php';

// Получение данных из формы регистрации

$login = $_POST['login'];
$password = $_POST['password'];
$sensorId = $_POST['sensorId'];
// Запись данных в базу данных

$connect = getDB();


$checkQuery = "SELECT * FROM `users` WHERE `login` = ?";
$stmt = $connect->prepare($checkQuery);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Пользователь с таким логином уже существует
    echo 'Данный пользователь уже зарегистрирован :(';
} else{
$connect = getDB();

$sql = "INSERT INTO `users` (login, password, sensorId) VALUES ('$login', '$password','$sensorId')";
if ($connect -> query($sql) === TRUE) {
    // echo 'Регистрация прошла успешно!';
    header("Location: /login.html");
} else {
    echo 'Данный пользователь уже зарегистрирован :(';
}
}
?>