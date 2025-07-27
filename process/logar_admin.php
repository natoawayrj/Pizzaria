<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once('conn.php'); // Conecta no banco

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Busca o usuário na nova tabela 'usuarios_admin'
        $stmt = $conn->prepare("SELECT * FROM usuarios_admin WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $admin_user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ========================================================================
        // ALTERAÇÃO: Verificando a senha com password_verify()
        // ========================================================================
        if ($admin_user && password_verify($senha, $admin_user['senha'])) {
            // Login de administrador realizado com sucesso!
            $_SESSION['admin_id'] = $admin_user['id'];
            $_SESSION['admin_nome'] = $admin_user['nome'];
            $_SESSION['admin_logado'] = true; // Flag para identificar a sessão do admin

            // Limpa a sessão de cliente, caso o mesmo navegador estivesse logado como cliente
            unset($_SESSION['usuario']);
            unset($_SESSION['cliente_id']);
            unset($_SESSION['last_pedido_id']);
            $_SESSION['show_login_alert'] = true; 

            $_SESSION["msg"] = "Bem-vindo, Administrador " . $admin_user['nome'] . "!";
            $_SESSION["status"] = "success";
            header("Location: ../gerenciar.php"); // Redireciona para a página de gerenciar
            exit();

        } else {
            $_SESSION["msg"] = "E-mail ou senha de administrador incorretos!";
            $_SESSION["status"] = "danger";
            header("Location: ../admin_login.php");
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION["msg"] = "Erro ao fazer login administrativo: " . $e->getMessage();
        $_SESSION["status"] = "danger";
        error_log("Erro PDO no login admin: " . $e->getMessage());
        header("Location: ../admin_login.php"); // Corrigido para a página de login de admin
        exit();
    }
} else {
    header("Location: ../admin_login.php");
    exit();
}
?>