<main class="render_main">
    <div class="myachiki">
        <img id="v_l" src="img/MYACH.png" alt="изображение">
        <img id="v_p" src="img/MYACH.png" alt="изображение">
    </div>
    <form action="auth_process.php" method="post" class="autoriz">
        <h1>РЕГИСТРАЦИЯ</h1>
        <?php if(isset($_GET['error'])): ?>
            <div class="error-message">
                <?php 
                if($_GET['error'] == 1) echo "Пароли не совпадают";
                elseif($_GET['error'] == 2) echo "Пользователь с таким email уже существует";
                else echo "Ошибка регистрации";
                ?>
            </div>
        <?php endif; ?>
        <input class="input_standart" type="email" name="email" placeholder="Введите почту" required>
        <input class="input_standart" type="password" name="password" placeholder="Пароль" required>
        <input class="input_standart" type="password" name="confirm_password" placeholder="Подтвердите пароль" required>
        <input type="hidden" name="action" value="register">
        <input class="autoriz_reg_button" type="submit" value="Зарегистрироваться">
        <a class="reg_button" href="index.php?page=autoriz">Авторизоваться</a>
    </form>
    <div class="myachiki">
        <img id="n_l" src="img/MYACH.png" alt="изображение">
        <img id="n_p" src="img/MYACH.png" alt="изображение">
    </div>
</main>