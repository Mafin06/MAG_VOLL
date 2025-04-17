<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Определяем страницу для отображения
$page = isset($_GET['page']) ? $_GET['page'] : 'main';

// Подключаем соответствующий заголовок
if($page == 'catalog' || $page == 'product_page') {
    include_once("header-catalog.php");
} else {
    include_once("header.php");
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверяем авторизацию пользователя
$isLoggedIn = isset($_SESSION['user_id']);
$userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';

// Подключаем контент страницы
switch($page) {
    case 'autoriz':
        include('autoriz.php');
        break;
    case 'register':
        include('register.php');
        break;
    case 'catalog':
        include('catalog.php');
        break;
    case 'product_page':
        include('product_page.php');
        break;
    case 'checkout':
        include('checkout.php');
        break;
    case 'accaunt':
        include('accaunt.php');
        break;
    case 'korzina':
        if($isLoggedIn) {
            include('korzina.php');
        } else {
            header("Location: index.php?page=autoriz");
            exit();
        }
        break;
    case 'logout':
        session_destroy();
        header("Location: index.php");
        exit();
        break;
    default:
        include('main.php');
}

include_once("footer.php");
?>