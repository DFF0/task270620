<?php
require_once 'includes/header.php';

if (
    isset($_POST['save']) && $_POST['save'] == 'reg' &&
    isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['login']) && !empty($_POST['login']) &&
    isset($_POST['full_name']) && !empty($_POST['full_name']) &&
    isset($_POST['pass']) && !empty($_POST['pass'])
) {
    $stmt = $db->prepare(
        "SELECT id FROM Users WHERE login = :login"
    );
    $stmt->execute(['login' => $_POST['login']]);
    $userId = $stmt->fetchColumn();

    if ( empty($userId) ) {
        try {
            $stmt = $db->prepare(
                "INSERT INTO Users SET `email` = :email, `login` = :login, `full_name` = :full_name, `pass` = :pass"
            );
            $stmt->execute([
                'email' => $_POST['email'],
                'login' => $_POST['login'],
                'full_name' => $_POST['full_name'],
                'pass' => $_POST['pass'],
            ]);

            $_SESSION['user_auth'] = [
                'id' => $db->lastInsertId(),
                'email' => $_POST['email'],
                'login' => $_POST['login'],
                'full_name' => $_POST['full_name'],
            ];

            $_SESSION['success'][] = 'Регистрация прошла успешно';

            header( "Location: /user.php" );
            exit;
        } catch (PDOException $e) {
            $_SESSION['errors'][] = 'Не удалось создать пользователя: ' . $e->getMessage();
        } finally {
            unset($_POST['save']);
        }
    } else {
        $_SESSION['errors'][] = 'Пользователь с таким логином уже существует';
    }
}

?>
<html>
<? require_once 'layouts/head.php'; ?>
<body>
<? require_once 'layouts/msg.php'; ?>

<div class="form-content">
    <div class="form-title"><strong>Регистрация</strong></div>
    <form action="" method="post">
        <input type="hidden" value="reg" name="save">
        <div class="form-input">
            <input type="email" name="email" placeholder="E-Mail" value="<?=isset($_POST['email'])? $_POST['email'] : ''?>" required>
        </div>
        <div class="form-input">
            <input type="text" name="login" placeholder="Логин" value="<?=isset($_POST['login'])? $_POST['login'] : ''?>" required>
        </div>
        <div class="form-input">
            <input type="text" name="full_name" placeholder="ФИО" value="<?=isset($_POST['full_name'])? $_POST['full_name'] : ''?>" required>
        </div>
        <div class="form-input">
            <input type="password" name="pass" placeholder="Пароль" required>
        </div>
        <div class="form-input">
            <button type="submit">Регистрация</button>
        </div>
    </form>
    <div><a href="/">Вернуться</a></div>
</div>

</body>
</html>