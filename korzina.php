<main class="render_main">
    <h1>Ваша корзина</h1>
    <div class="cart_container">
        <?php
        $cartItems = getCartItems();
        
        if($cartItems->num_rows > 0) {
            while($item = $cartItems->fetch_assoc()) {
                echo '<div class="product_card korzina">
                    <div class="product_card_img korz">
                        <img src="'.$item['image_path'].'" alt="'.$item['name'].'">
                    </div>
                    <div class="item_info">
                        <h3>'.$item['name'].'</h3>
                        '.($item['size'] ? '<p>Размер: '.$item['size'].'</p>' : '').'
                        <p>Цена: '.number_format($item['price'], 2).' ₽</p>
                        
                        <div class="quantity_control">
                            <span>Количество: '.$item['quantity'].'</span>
                        </div>
                    </div>
                    <div class="item_actions">
                        <a href="cart_process.php?action=remove&id='.$item['id'].'" class="remove_button">Удалить</a>
                    </div>
                </div>';
            }
            
            echo '<div class="cart_total">
                <p>Итого: '.calculateCartTotal().' ₽</p>
                <a href="index.php?page=checkout" class="checkout_button">Оформить заказ</a>
            </div>';
        } else {
            echo '<p>Ваша корзина пуста</p>';
        }
        ?>
    </div>
</main>