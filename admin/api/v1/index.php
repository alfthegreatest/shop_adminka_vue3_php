<?php
header('Access-Control-Allow-Origin: *');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
    exit(0);
}


// Подключаем библиотеки и хелперы
include_once 'lib/underscore.php';
include_once 'common/helpers.php';

// Получаем данные из запроса
$data = \Helpers\getRequestData();

$router = $data['router'];
if ($data['method'] === 'DELETE' || $data['method'] === 'PUT') {
    $router = explode('/', $data['router'])[0];
}

// Проверяем роутер на валидность
if (\Helpers\isValidRouter($router)) {
    // Подключаем файл-роутер
    include_once "routers/$router.php";

    // Запускаем главную функцию
    route($data);
} else {
    // Выбрасываем ошибку
    \Helpers\throwHttpError('invalid_router', 'router not found');
}
