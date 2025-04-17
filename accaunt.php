<main class="render_main">
    <div class="myachiki">
        <img id="v_l" src="img/MYACH.png" alt="изображение">
        <img id="v_p" src="img/MYACH.png" alt="изображение">
    </div>
    
    <div class="account_container">
        <h1>Личный кабинет</h1>
        
        <div class="account_info">
            <div class="info_row">
                <span class="info_label">Email:</span>
                <span class="info_value"><?= htmlspecialchars($_SESSION['user_email']) ?></span>
            </div>
            
            <div class="info_row">
                <span class="info_label">Дата регистрации:</span>
                <span class="info_value">
                    <?php
                    // Получаем дату регистрации из БД
                    global $conn;
                    $stmt = $conn->prepare("SELECT created_at FROM users WHERE id = ?");
                    $stmt->bind_param("i", $_SESSION['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $userData = $result->fetch_assoc();
                    echo date('d.m.Y', strtotime($userData['created_at']));
                    ?>
                </span>
            </div>
        </div>
        
        <h2>История заказов</h2>
        <div class="order_history">
            <?php
            $orders = getOrdersByUser($_SESSION['user_id']);
            
            if($orders->num_rows > 0): 
                while($order = $orders->fetch_assoc()):
            ?>
            <div class="order">
                <div class="order_header">
                    <span class="order_id">Заказ #<?= $order['id'] ?></span>
                    <span class="order_date"><?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></span>
                    <span class="order_status"><?= $order['status'] ?></span>
                    <span class="order_total"><?= number_format($order['total'], 2) ?> ₽</span>
                </div>
                
                <div class="order_items">
                    <?php
                    $items = getOrderItems($order['id']);
                    while($item = $items->fetch_assoc()):
                    ?>
                    <div class="order_item">
                        <img src="<?= $item['image_path'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="item_info">
                            <h4><?= htmlspecialchars($item['name']) ?></h4>
                            <p>Кол-во: <?= $item['quantity'] ?></p>
                            <?php if($item['size']): ?>
                                <p>Размер: <?= $item['size'] ?></p>
                            <?php endif; ?>
                            <p class="price"><?= number_format($item['price'], 2) ?> ₽</p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php 
                endwhile;
            else: 
            ?>
            <p>У вас пока нет завершенных заказов</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="myachiki">
        <img id="n_l" src="img/MYACH.png" alt="изображение">
        <img id="n_p" src="img/MYACH.png" alt="изображение">
    </div>
</main>