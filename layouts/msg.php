<?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
    <? foreach ($_SESSION['errors'] as $error): ?>
        <div class="error-msg">
            <?=$error?>
        </div>
    <? endforeach; ?>
    <? unset($_SESSION['errors']); ?>
<? endif;

if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
    <? foreach ($_SESSION['success'] as $success): ?>
        <div class="success-msg">
            <?=$success?>
        </div>
    <? endforeach; ?>
    <? unset($_SESSION['success']); ?>
<? endif;