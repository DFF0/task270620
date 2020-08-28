<?php
require_once 'includes/header.php';

if ( isset($_SESSION['user_auth']) && !empty($_SESSION['user_auth']) && !empty($_SESSION['user_auth']['login']) ) {
    header( "Location: /user.php" );
    exit;
}

if (
    isset($_POST['save']) && $_POST['save'] == 'auth' &&
    isset($_POST['login']) && !empty($_POST['login']) &&
    isset($_POST['pass']) && !empty($_POST['pass'])
) {
    $stmt = $db->prepare(
        "SELECT * FROM Users WHERE login = :login and pass = :pass"
    );
    $stmt->execute(['login' => $_POST['login'], 'pass' => $_POST['pass']]);
    $user = $stmt->fetch(PDO::FETCH_LAZY);

    if (!empty($user)) {
        $_SESSION['user_auth'] = [
            'id' => $user->id,
            'email' => $user->email,
            'login' => $user->login,
            'full_name' => $user->full_name,
        ];

        unset($_POST['save']);

        header( "Location: /user.php" );
        exit;
    } else {
        $_SESSION['errors'][] = 'Неверный логин или пароль';
    }
}

?>
<html>
<? require_once 'layouts/head.php'; ?>
<body>
<? require_once 'layouts/msg.php'; ?>

<div class="form-content">
    <div class="form-title"><strong>Авторизация</strong></div>
    <form action="/" method="post">
        <input type="hidden" value="auth" name="save">
        <div class="form-input">
            <input type="text" name="login" placeholder="Логин" value="<?=isset($_POST['login'])? $_POST['login'] : ''?>" required>
        </div>
        <div class="form-input">
            <input type="password" name="pass" placeholder="Пароль" required>
        </div>
        <div class="form-input">
            <button type="submit">Войти</button>
        </div>
    </form>
    <div><a href="registration.php">Регистрация</a></div>
</div>

<div>
    <div>список email'лов встречающихся более чем у одного пользователя:</div>
    <div>SQL: <strong>select email from Users group by email having COUNT(email) > 1</strong></div>
    <div>
        <? $stmt = $db->prepare(
            "select email from Users group by email having COUNT(email) > 1"
        );
        $stmt->execute(); ?>
        Результат:<br>

        <? while ($row = $stmt->fetch(PDO::FETCH_LAZY)): ?>
            <?=$row->email?><br>
        <? endwhile; ?>
    </div>
</div>

<hr>

<div>
    <div>список логинов пользователей, которые не сделали ни одного заказа:</div>
    <div>SQL: <strong>select login from Users where id not in (select user_id from Orders)</strong></div>
    <div>
        <? $stmt = $db->prepare(
            "select login from Users where id not in (select user_id from Orders)"
        );
        $stmt->execute(); ?>
        Результат:<br>

        <? while ($row = $stmt->fetch(PDO::FETCH_LAZY)): ?>
            <?=$row->login?><br>
        <? endwhile; ?>
    </div>
</div>

<hr>

<div>
    <div>список логинов пользователей которые сделали более двух заказов:</div>
    <div>SQL: <strong>select u.login from Users u, Orders o where o.user_id = u.id group by u.login having COUNT(u.login) > 2</strong></div>
    <div>
        <? $stmt = $db->prepare(
            "select u.login from Users u, Orders o where o.user_id = u.id group by u.login having COUNT(u.login) > 2"
        );
        $stmt->execute(); ?>
        Результат:<br>

        <? while ($row = $stmt->fetch(PDO::FETCH_LAZY)): ?>
            <?=$row->login?><br>
        <? endwhile; ?>
    </div>
</div>

</body>
</html>