<?php
require_once 'header.php';
unset($_SESSION['user_auth']);

header( "Location: /" );
exit;