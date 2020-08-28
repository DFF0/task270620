<?php
require_once 'includes/header.php';

if ( !isset($_SESSION['user_auth']) || empty($_SESSION['user_auth']) || empty($_SESSION['user_auth']['login']) ) {
    $_SESSION['errors'][] = 'Необходима авторизация!';
    header( "Location: /" );
    exit;
}

if (
    isset($_POST['save']) && $_POST['save'] == 'order' &&
    isset($_POST['price'])
) {
    try {
        $stmt = $db->prepare(
            "INSERT INTO Orders SET `user_id` = :user_id, `price` = :price"
        );
        $stmt->execute([
            'user_id' => $_SESSION['user_auth']['id'],
            'price' => (int) $_POST['price'],
        ]);

        $_SESSION['success'][] = 'Заказ прошел успешно';

    } catch (PDOException $e) {
        $_SESSION['errors'][] = 'Не удалось сделать заказ: ' . $e->getMessage();
    } finally {
        unset($_POST['save']);
    }
}

if (
    isset($_POST['save']) && $_POST['save'] == 'edit' &&
    isset($_POST['full_name']) && !empty($_POST['full_name']) &&
    isset($_POST['pass']) && !empty($_POST['pass'])
) {
    try {
        $stmt = $db->prepare(
            "UPDATE Users SET `full_name` = :full_name, `pass` = :pass WHERE `id` = :id"
        );
        $stmt->execute([
            'full_name' => $_POST['full_name'],
            'pass' => $_POST['pass'],
            'id' => $_SESSION['user_auth']['id'],
        ]);

        $_SESSION['user_auth']['full_name'] = $_POST['full_name'];

        $_SESSION['success'][] = 'Пользователь отредактирован';

    } catch (PDOException $e) {
        $_SESSION['errors'][] = 'Не удалось отредактировать пользователя: ' . $e->getMessage();
    } finally {
        unset($_POST['save']);
    }
}

?>
<html>
<? require_once 'layouts/head.php'; ?>
<body>
<? require_once 'layouts/msg.php'; ?>

<div class="header">
    Вы вошли как <?=$_SESSION['user_auth']['full_name']?> (<a href="includes/unauth.php">Выйти</a>)
</div>

<div style="" class="row">
    <div>
        <div class="form-content">
            <div class="form-title"><strong>Сделать заказ</strong></div>
            <form action="/user.php" method="post">
                <input type="hidden" value="order" name="save">
                <div class="form-input">
                    <input type="number" name="price" placeholder="Цена" value="" required>
                </div>
                <div class="form-input">
                    <button type="submit">Добавить</button>
                </div>
            </form>
        </div>
    </div>
    <div>
        <div class="form-content">
            <div class="form-title"><strong>Редактировать профиль</strong></div>
            <form action="/user.php" method="post">
                <input type="hidden" value="edit" name="save">
                <div class="form-input">
                    <input type="text" name="full_name" placeholder="ФИО" value="<?=isset($_POST['full_name'])? $_POST['full_name'] : $_SESSION['user_auth']['full_name']?>" required>
                </div>
                <div class="form-input">
                    <input type="password" name="pass" placeholder="Пароль" required>
                </div>
                <div class="form-input">
                    <button type="submit">Применить</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>