<?php
session_start();        // Inicia a sessão
session_destroy();      // Destroi todas as variáveis de sessão
header("Location: index.php"); // Redireciona para a home
exit();                 // Garante que o script termine aqui
