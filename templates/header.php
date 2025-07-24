<?php
session_start(); // Inicia a sessão

include_once(__DIR__ . '/../process/conn.php'); // Conecta ao banco de dados
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizzaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-pizzaria sticky-top">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand">
                <img src="img/logo-certo.jpg" alt="pizza" id="brand-logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item active">
                        <a href="index.php" class="nav-link">Home</a>
                    </li>

                    <?php if (!isset($_SESSION['usuario']) && !isset($_SESSION['admin_logado'])): // Se NENHUM dos dois estiver logado ?>
                        <li class="nav-item active">
                            <a href="cadastro.php" class="nav-link">Criar Conta</a>
                        </li>
                        <li class="nav-item active">
                            <a href="login.php" class="nav-link">Login Cliente</a>
                        </li>
                        <li class="nav-item active">
                            <a href="admin_login.php" class="nav-link text-warning">Login Admin</a> </li>
                    <?php elseif (isset($_SESSION['usuario'])): // Se o cliente estiver logado ?>
                        <li class="nav-item active">
                            <a href="cardapio.php" class="nav-link">Cardápio</a>
                        </li>
                        <li class="nav-item active">
                            <a href="dashboard.php" class="nav-link">Monte seu pedido</a>
                        </li>
                        <li class="nav-item active">
                            <a href="carrinho.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Carrinho</a>
                        </li>
                        <li class="nav-item active">
                            <a href="logout.php" class="nav-link ">Logout</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true): // Se o administrador estiver logado ?>
                        <li class="nav-item active">
                            <a href="gerenciar.php" class="nav-link">Gerenciar</a>
                        </li>
                        <li class="nav-item active">
                            <a href="admin_logout.php" class="nav-link">Logout Admin</a> </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>