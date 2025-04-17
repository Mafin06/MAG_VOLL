<?php
if(!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}
?>
<main class="render_main">
    <div class="order_success">
        <h1>Заказ успешно оформлен!</h1>
        <p>Номер вашего заказа: #<?= htmlspecialchars($_GET['order_id']) ?></p>
        <p>Мы свяжемся с вами для подтверждения заказа.</p>
        <div class="success_actions">
            <a href="index.php?page=accaunt" class="button_standart">Мои заказы</a>
            <a href="index.php?page=catalog" class="button_secondary">Продолжить покупки</a>
        </div>
    </div>
</main>