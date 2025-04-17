<main class="render_main">
    <section class="catalog_section">
        <h2>Категория/ <?= isset($_GET['category']) ? htmlspecialchars($_GET['category']) : 'Все товары' ?></h2>
        <div class="container_with_product_cards">
            <?php
            $category = isset($_GET['category']) ? $_GET['category'] : null;
            $search = isset($_GET['search']) ? $_GET['search'] : null;
            $products = getProducts($category, $search);
            
            if($products->num_rows > 0) {
                while($product = $products->fetch_assoc()) {
                    $inCart = isProductInCart($product['id']);
                    $quantityInCart = $inCart ? getProductQuantityInCart($product['id']) : 0;
                    
                    echo '<div class="product_card">
                        <div class="product_card_img">
                            <a href="index.php?page=product_page&id='.$product['id'].'"><img src="'.$product['image_path'].'" alt="фото товара"></a>
                        </div>
                        <div class="catalog_card_items">
                            <div class="price_desc_raiting_container">
                                <p>'.number_format($product['price'], 2).' ₽</p>
                                <p>'.$product['name'].'</p>
                                <p>4,8 ★</p>
                            </div>
                            <div class="quantity_controls">';
                    
                    if($inCart) {
                        echo '<span class="in_cart_badge">В корзине: '.$quantityInCart.'</span>';
                    } else {
                        echo '<a href="cart_process.php?action=add&product_id='.$product['id'].'" class="buy_button_korzina"><img src="img/buy_button.png" alt="изображение"></a>';
                    }
                    
                    echo '</div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p>Товары не найдены</p>';
            }
            ?>
        </div>
    </section>
</main>