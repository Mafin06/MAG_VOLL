<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if($action == 'login') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        
        if(empty($email) || empty($password)) {
            header("Location: index.php?page=autoriz&error=1");
            exit();
        }
        
        if(loginUser($email, $password)) {
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.php?page=autoriz&error=1");
            exit();
        }
    } elseif($action == 'register') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);
        
        if(empty($email) || empty($password) || empty($confirmPassword)) {
            header("Location: index.php?page=register&error=3");
            exit();
        }
        
        if($password !== $confirmPassword) {
            header("Location: index.php?page=register&error=1");
            exit();
        }
        
        if(strlen($password) < 6) {
            header("Location: index.php?page=register&error=4");
            exit();
        }
        
        // Проверяем, существует ли уже пользователь
        global $conn;
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if($stmt->num_rows > 0) {
            header("Location: index.php?page=register&error=2");
            exit();
        }
        
        if(registerUser($email, $password)) {
            // Автоматически входим после регистрации
            loginUser($email, $password);
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.php?page=register&error=3");
            exit();
        }
    }
}

header("Location: index.php");
?>