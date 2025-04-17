<?php
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = getProductById($productId);

if(!$product) {
    header("Location: index.php");
    exit();
}

$inCart = isProductInCart($productId);
?>
<main class="render_main">
    <section class="product_section">
        <div class="product_images">
            <div class="main_image">
                <img src="<?= $product['image_path'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <!-- <div class="thumbnails">
                Здесь можно добавить миниатюры для слайдера
                <img src="<?= $product['image_path'] ?>" alt="Миниатюра 1">
            </div> -->
        </div>
        <div class="product_info">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="price"><?= number_format($product['price'], 2) ?> ₽</div>
            <div class="rating">4,8 ★</div>
            <div class="description">
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>
            
            <div class="actions">
                <form action="cart_process.php" method="get">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <?php if($product['category'] == 'Женщинам' || $product['category'] == 'Мужчинам' || $product['category'] == 'Детям и Подросткам'): ?>
                        <div class="size_selector ">
                            <label>Размер:</label>
                            <select name="size" required>
                                <option value="">Выберите размер</option>
                                <?php 
                                $sizes = explode(',', $product['sizes']);
                                foreach($sizes as $size): 
                                    $size = trim($size);
                                ?>
                                    <option value="<?= $size ?>" <?= ($item['size'] ?? '') == $size ? 'selected' : '' ?>>
                                        <?= $size ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="quantity_selector">
                        <label>Количество:</label>
                        <input type="number" name="quantity" value="1" min="1" max="10">
                    </div>
                    
                    <div class="buttons">
                        <button type="submit" class="buy_button add_to_korzina">
                            <?= $inCart ? 'Добавить еще' : 'Добавить в корзину' ?>
                        </button>
                        <a href="index.php?page=korzina" class="buy_button">Купить сейчас</a>
                    </div>
                </form>
            </div>
            <!-- <div class="actions">
                <a href="index.php?page=korzina" class="buy_button">Купить</a>
                <?php if($inCart): ?>
                    <span class="in_cart_message">Товар уже в корзине</span>
                <?php else: ?>
                    <a href="cart_process.php?action=add&product_id=<?= $product['id'] ?>" class="buy_button add_to_korzina"><img src="img/buy_button.png" alt="изображение"> Добавить в корзину</a>
                <?php endif; ?>
            </div> -->
        </div>
    </section>
</main>