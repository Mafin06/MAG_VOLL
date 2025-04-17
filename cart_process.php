<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=autoriz");
    exit();
}

$action = $_GET['action'] ?? '';
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$size = $_GET['size'] ?? null;
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

switch($action) {
    case 'add':
        if($productId > 0) {
            addToCart($productId, $size, $quantity);
        }
        break;
        
    case 'remove':
        $cartItemId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if($cartItemId > 0) {
            removeFromCart($cartItemId);
        }
        break;
        
    case 'update':
        $cartItemId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $newQuantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
        if($cartItemId > 0 && $newQuantity > 0) {
            updateCartQuantity($cartItemId, $newQuantity);
        }
        break;
}

header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>