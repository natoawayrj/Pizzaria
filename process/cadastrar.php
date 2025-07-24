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

      
  
      try {
          $stmt = $conn->prepare("INSERT INTO clientes (nome, telefone, endereco, email, senha) VALUES (:nome, :telefone, :endereco, :email, :senha)");
  
          $stmt->execute([
              ':nome' => $nome,
              ':telefone' => $telefone,
              ':endereco' => $endereco,
              ':email' => $email,
              ':senha' => $senha
            
          ]);
  
          echo "<p>Cadastro realizado com sucesso!</p>";
          echo "<a href='login.php'>Fazer login</a>";
  
      } catch (PDOException $e) {
          echo "Erro ao cadastrar: " . $e->getMessage();
      }
    }
?>