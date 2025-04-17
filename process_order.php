<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=autoriz");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $orderData = [
        'fullname' => trim($_POST['fullname']),
        'phone' => trim($_POST['phone']),
        'address' => trim($_POST['address']),
        'payment_method' => $_POST['payment'],
        'total' => calculateCartTotal()
    ];
    
    // Создаем заказ
    $orderId = placeOrder($_SESSION['user_id'], $orderData);

    if($orderId) {
        // Для отладки - можно потом удалить
        error_log("Order created successfully. ID: ".$orderId);
        header("Location: index.php?page=order_success&order_id=".$orderId);
        exit();
    } else {
        error_log("Failed to create order");
        $_SESSION['checkout_error'] = "Произошла ошибка при оформлении заказа";
        header("Location: index.php?page=checkout");
        exit();
    }

    if($orderId) {
        // Успешное создание заказа - перенаправляем на страницу успеха
        header("Location: index.php?page=order_success&order_id=".$orderId);
        exit(); // Важно добавить exit после header
    } else {
        // Ошибка при создании заказа
        $_SESSION['checkout_error'] = "Произошла ошибка при оформлении заказа";
        header("Location: index.php?page=checkout");
        exit();
    }
}
if($orderId) {
    // Вместо header используем HTML-редирект
    echo '<!DOCTYPE html><html><head>
          <meta http-equiv="refresh" content="0;url=index.php?page=order_success&order_id='.$orderId.'">
          </head><body></body></html>';
    exit();
}
// Если запрос не POST - перенаправляем на главную
header("Location: index.php");
exit();
?>
