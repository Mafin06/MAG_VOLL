<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAG_VOLL</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <nav>
            <div class="logo_container">
                <a href="index.php"><img src="img/logo.png" alt="логотип"></a>
            </div>
            <div class="form_searching">
                <form action="index.php?page=catalog" method="get">
                    <input type="hidden" name="page" value="catalog">
                    <input id="search_row" type="text" name="search" placeholder="Поиск" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <input id="poisk_button" type="submit" value="Найти">
                </form>
            </div>
            <div class="profile_container">
                <div class="icon_person">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=accaunt"><img src="img/person_img.png" alt="иконка"></a>
                    <?php else: ?>
                        <img src="img/person_img.png" alt="иконка">
                    <?php endif; ?>
                    <a href="<?= isset($_SESSION['user_id']) ? 'index.php?page=korzina' : 'index.php?page=autoriz' ?>">
                        <img src="img/buy_button.png" alt="иконка">
                    </a>
                </div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span class="user-email"><?= htmlspecialchars($_SESSION['user_email']) ?></span>
                    <a id="vhod_a" href="index.php?page=logout">Выйти</a>
                <?php else: ?>
                    <a id="vhod_a" href="index.php?page=autoriz">Войти</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>