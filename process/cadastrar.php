<?php

include_once('conn.php');

$method = $_SERVER["REQUEST_METHOD"];

if($method === "GET"){

}elseif($method ==="POST"){
      // Pegando os dados enviados
      $nome = $_POST['nome'];
      $telefone = $_POST['telefone'];
      $endereco = $_POST['endereco'];
      $email = $_POST['email'];
      $senha = $_POST['senha'];

      // ========================================================================
      // ALTERAÇÃO: Criando um hash seguro da senha antes de salvar
      // ========================================================================
      $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
  
      try {
          $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone, endereco, email, senha) VALUES (:nome, :telefone, :endereco, :email, :senha)");
  
          $stmt->execute([
              ':nome' => $nome,
              ':telefone' => $telefone,
              ':endereco' => $endereco,
              ':email' => $email,
              ':senha' => $senha_hash // Salva o hash, não a senha original
          ]);
  
          echo "<p>Cadastro realizado com sucesso!</p>";
          echo "<a href='../login.php'>Fazer login</a>"; // Corrigido o caminho para o login
  
      } catch (PDOException $e) {
          echo "Erro ao cadastrar: " . $e->getMessage();
      }
    }
?>