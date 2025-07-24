<?php
  
/*definido variáveis para a conexão com o banco, setamos as variáveis 
com as informações do banco, usuário,senha, projeto e o host*/ 
   $user = "root";
   $pass ="";
   $db = "pizzaria";
   $host = "localhost";

//gerando e verificando se a conexão está sendo feita

   try{
      $conn = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      
   

   } catch(PDOException $e){
      print "Erro: " .$e ->getMessage() . "</br>";
      die();
   }
?>