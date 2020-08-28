<?php
session_start();
define('ABSOLUTE_PATH', $_SERVER['DOCUMENT_ROOT'].'/');

require_once ABSOLUTE_PATH.'config/config.php';
require_once ABSOLUTE_PATH.'includes/pdo.php';

define('FILE_NAME', str_replace("/", "", $_SERVER["SCRIPT_NAME"]));

$title["index.php"] = 'Тестовое задание';
$title["registration.php"] = 'Регистрация';
$title["user.php"] = 'Личный кабинет';
