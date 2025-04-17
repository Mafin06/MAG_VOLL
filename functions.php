<?php
function registerUser($email, $password) {
    global $conn;
    
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashedPassword);
        
        $result = $stmt->execute();
        
        if(!$result) {
            error_log("Ошибка регистрации: " . $stmt->error);
            return false;
        }
        
        return true;
    } catch(Exception $e) {
        error_log("Ошибка при регистрации: " . $e->getMessage());
        return false;
    }
}

function loginUser($email, $password) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                return true;
            }
        }
        return false;
    } catch(Exception $e) {
        error_log("Ошибка при авторизации: " . $e->getMessage());
        return false;
    }
}

function getProducts($category = null, $search = null) {
    global $conn;
    
    $sql = "SELECT * FROM products";
    $params = [];
    
    if($category) {
        $sql .= " WHERE category = ?";
        $params[] = $category;
    }
    
    if($search) {
        $sql .= $category ? " AND" : " WHERE";
        $sql .= " name LIKE ?";
        $params[] = "%$search%";
    }
    
    $stmt = $conn->prepare($sql);
    
    if(!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt->get_result();
}

function addToCart($productId, $size = null, $quantity = 1) {
    if(!isset($_SESSION['user_id'])) return false;
    
    global $conn;
    $userId = $_SESSION['user_id'];
    
    // Проверяем, есть ли уже такой товар в корзине
    $stmt = $conn->prepare("SELECT id, quantity FROM cart 
                           WHERE user_id = ? AND product_id = ? AND (size = ? OR size IS NULL)");
    $sizeToCheck = $size ?? null;
    $stmt->bind_param("iis", $userId, $productId, $sizeToCheck);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        // Товар уже есть - увеличиваем количество
        $item = $result->fetch_assoc();
        $newQuantity = $item['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $newQuantity, $item['id']);
    } else {
        // Товара нет - добавляем новый
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, size, quantity) 
                               VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $userId, $productId, $size, $quantity);
    }
    
    return $stmt->execute();
}

function removeFromCart($cartItemId) {
    if(!isset($_SESSION['user_id'])) return false;
    
    global $conn;
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cartItemId, $userId);
    return $stmt->execute();
}

function getCartItems() {
    if(!isset($_SESSION['user_id'])) return [];
    
    global $conn;
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT c.id, p.id as product_id, p.name, p.price, p.image_path, c.quantity, c.size 
                          FROM cart c 
                          JOIN products p ON c.product_id = p.id 
                          WHERE c.user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result();
}

function getProductById($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function isProductInCart($productId) {
    if(!isset($_SESSION['user_id'])) return false;
    
    global $conn;
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0;
}

function calculateCartTotal() {
    if(!isset($_SESSION['user_id'])) return 0;
    
    global $conn;
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT SUM(p.price * c.quantity) as total 
                           FROM cart c 
                           JOIN products p ON c.product_id = p.id 
                           WHERE c.user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc()['total'];
    
    return $total ? number_format($total, 2) : '0.00';
}

function getProductQuantityInCart($productId) {
    if(!isset($_SESSION['user_id'])) return 0;
    
    global $conn;
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc()['total'] ?? 0;
}

function updateCartQuantity($cartItemId, $quantity) {
    if(!isset($_SESSION['user_id'])) return false;
    
    global $conn;
    $userId = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $quantity, $cartItemId, $userId);
    return $stmt->execute();
}

// Добавим новые функции

function placeOrder($userId, $orderData) {
    global $conn;
    
    try {
        $conn->begin_transaction();
        
        // Создаем заказ
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total, fullname, phone, address, payment_method) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idssss", 
            $userId,
            $orderData['total'],
            $orderData['fullname'],
            $orderData['phone'],
            $orderData['address'],
            $orderData['payment_method']
        );
        $stmt->execute();
        $orderId = $conn->insert_id;
        
        // Добавляем товары из корзины
        $cartItems = getCartItems();
        while($item = $cartItems->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO order_items 
                                  (order_id, product_id, quantity, size, price) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisd", 
                $orderId,
                $item['product_id'],
                $item['quantity'],
                $item['size'],
                $item['price']
            );
            $stmt->execute();
        }
        
        // Очищаем корзину
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        $conn->commit();
        return $orderId;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Order error: " . $e->getMessage());
        return false;
    }
}

function getOrdersByUser($userId) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result();
}

function getOrderItems($orderId) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT oi.*, p.name, p.image_path 
                           FROM order_items oi
                           JOIN products p ON oi.product_id = p.id
                           WHERE oi.order_id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    return $stmt->get_result();
}
?>