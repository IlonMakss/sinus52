<?php

const DB_HOST = "localhost"; // сервер
const DB_USER = "macsimxn_bd"; // имя пользователя
const DB_PASS = "Maxmax1"; // пароль
const DB_NAME = "macsimxn_bd"; // название базы данных

// Подключение к базе данных
function getDB() {
    return mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);


}
// Проверка подключения

