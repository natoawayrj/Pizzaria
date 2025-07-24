<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once('conn.php'); // Conecta no banco

$method = $_SERVER["REQUEST_METHOD"];

$login_page_url = "../login.php"; 

if ($method === "POST") {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Valida se os campos não estão vazios
    if (empty($email) || empty($senha)) {
        $_SESSION['login_error'] = "Por favor, preencha o e-mail e a senha.";
        header("Location: " . $login_page_url);
        exit();
    }

    try {
        // Procura o usuário no banco
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ========================================================================
        // ALTERAÇÃO: Voltando para a verificação de senha em texto puro
        // Compara a senha digitada diretamente com a senha salva no banco.
        // ========================================================================
        if ($user && $senha == $user['senha']) {
            
            // Login realizado com sucesso!
            $_SESSION['cliente_id'] = $user['id'];
            $_SESSION['usuario'] = $user['nome'];
            $_SESSION['show_login_alert'] = true; 

            // Limpa qualquer erro de login antigo da sessão
            unset($_SESSION['login_error']);

            // Redirecionar para a página inicial (home)
            header("Location: ../index.php");
            exit();

        } else {
            // Se o usuário não existe ou a senha está errada, define uma mensagem de erro.
            $_SESSION['login_error'] = "E-mail ou senha incorretos.";
            
            // Redireciona de volta para a página de login
            header("Location: " . $login_page_url);
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Ocorreu um erro no servidor. Tente novamente mais tarde.";
        header("Location: " . $login_page_url);
        exit();
    }
}
?>