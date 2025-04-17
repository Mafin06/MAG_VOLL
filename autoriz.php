<main class="render_main">
    <div class="myachiki">
        <img id="v_l" src="img/MYACH.png" alt="изображение">
        <img id="v_p" src="img/MYACH.png" alt="изображение">
    </div>
    <form action="auth_process.php" method="post" class="autoriz">
        <h1>АВТОРИЗАЦИЯ</h1>
        <?php if(isset($_GET['error'])): ?>
            <div class="error-message">Неверный email или пароль</div>
        <?php endif; ?>
        <input class="input_standart" type="email" name="email" placeholder="Введите почту" required>
        <input class="input_standart" type="password" name="password" placeholder="Пароль" required>
        <input type="hidden" name="action" value="login">
        <input class="autoriz_reg_button" type="submit" value="Войти">
        <a class="reg_button" href="index.php?page=register">Зарегистрироваться</a>
    </form>
    <div class="myachiki">
        <img id="n_l" src="img/MYACH.png" alt="изображение">
        <img id="n_p" src="img/MYACH.png" alt="изображение">
    </div>
</main>