<?php
session_start();

if (isset($_SESSION['carrinho'])) {
    unset($_SESSION['carrinho']);
}

header('Location: ../carrinho.php');
exit;
?>