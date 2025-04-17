
<main class="render_main">
    <div class="myachiki">
        <img id="v_l" src="img/MYACH.png" alt="изображение">
        <img id="v_p" src="img/MYACH.png" alt="изображение">
    </div>
    
    <div class="checkout_container">
        <h1>Оформление заказа</h1>
        
        <div class="checkout_columns">
            <div class="delivery_info">
                <h2>Данные для доставки</h2>
                <?php if(isset($_SESSION['checkout_error'])): ?>
                    <div class="error-message">
                        <?= $_SESSION['checkout_error'] ?>
                        <?php unset($_SESSION['checkout_error']); ?>
                    </div>
                <?php endif; ?>
                <form action="process_order.php" method="post" id="checkout_form" onsubmit="return validateCheckoutForm()">
                    <div class="form_group">
                        <label for="fullname">ФИО:</label><br>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>
                    
                    <div class="form_group">
                        <label for="phone">Телефон:</label><br>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    
                    <div class="form_group">
                        <label for="address">Адрес доставки:</label><br>
                        <textarea id="address" name="address" required></textarea>
                    </div>
                    
                    <div class="form_group">
                        <label for="comment">Комментарий к заказу:</label><br>
                        <textarea id="comment" name="comment"></textarea>
                    </div>
                    
                    <h2>Способ оплаты</h2>
                    <div class="payment_methods">
                        <label>
                            <input type="radio" name="payment" value="cash" checked> Наличными при получении
                        </label><br>
                        <label>
                            <input type="radio" name="payment" value="card"> Картой онлайн
                        </label>
                    </div>
                    
                    <input type="submit" class="remove_button submit_order" value="Подтвердить заказ">
                </f>
            </div>
            
            <div class="order_summary">
                <h2>Ваш заказ</h2>
                <div class="order_items">
                    <?php
                    $cartItems = getCartItems();
                    $total = 0;
                    
                    if($cartItems->num_rows > 0) {
                        while($item = $cartItems->fetch_assoc()) {
                            $sum = $item['price'] * $item['quantity'];
                            $total += $sum;
                            ?>
                            <div class="order_item">
                                <div class="item_image">
                                    <img src="<?= $item['image_path'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                </div>
                                <div class="item_details">
                                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                                    <?php if($item['size']): ?>
                                        <p>Размер: <?= $item['size'] ?></p>
                                    <?php endif; ?>
                                    <p>Количество: <?= $item['quantity'] ?></p>
                                    <p class="item_price"><?= number_format($item['price'], 2) ?> ₽ × <?= $item['quantity'] ?> = <?= number_format($sum, 2) ?> ₽</p>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                
                <div class="order_total">
                    <p>Итого: <span><?= number_format($total, 2) ?> ₽</span></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="myachiki">
        <img id="n_l" src="img/MYACH.png" alt="изображение">
        <img id="n_p" src="img/MYACH.png" alt="изображение">
    </div>
    <script>
        function validateCheckoutForm() {
            const fullname = document.getElementById('fullname').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const address = document.getElementById('address').value.trim();
            
            if(!fullname || !phone || !address) {
                alert('Пожалуйста, заполните все обязательные поля');
                return false;
            }
            
            return true;
        }
    </script>
</main>