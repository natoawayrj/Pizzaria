<?php
  include_once("templates/header.php");
  //include_once("process/conn.php");

  $bordas = $conn->query("SELECT * FROM bordas;")->fetchAll(PDO::FETCH_ASSOC);
  $massas = $conn->query("SELECT * FROM massas;")->fetchAll(PDO::FETCH_ASSOC);
  $sabores_disponiveis = $conn->query("SELECT * FROM sabores;")->fetchAll(PDO::FETCH_ASSOC); 
  $bebidas_disponiveis = $conn->query("SELECT * FROM bebidas;")->fetchAll(PDO::FETCH_ASSOC);
?>
    <div id="main-banner">
      <h1 class="text-center" style="color:#fff">Faça o seu pedido</h1>
    </div>
    <div id="main-container">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2>Monte sua pizza</h2>
            <form action="process/pizza.php" id="pizza-form" method="POST">
               <div class="form-group">
                <label for="borda">Borda:</label>
                <select name="borda" id="borda" class="form-select">
                  <option value="">Selecione a borda</option>
                  <?php foreach($bordas as $borda):?>
                    <option value="<?=$borda["id"]?>"><?=$borda["tipo"]?></option>
                  <?php endforeach; ?>
                </select>
               </div>
               <div class="form-group">
                <label for="massa">Massas:</label>
                <select name="massa" id="massa" class="form-select">
                  <option value="">Selecione a massa</option>
                  <?php foreach($massas as $massa):?>
                    <option value="<?=$massa["id"]?>"><?=$massa["tipo"]?></option>
                  <?php endforeach; ?>
                </select>
               </div>  
               <div class="form-group">
                <label for="sabores">Sabores: (Máximo 3)</label>
                <select multiple name="sabores[]" id="sabores" class="form-select">
                  <?php foreach($sabores_disponiveis as $sabor):?>
                    <option value="<?=$sabor["id"]?>"><?=$sabor["nome"]?></option>
                  <?php endforeach; ?>
                </select>
               </div>  
               <h2>Bebidas</h2>
               <div class="form-group">
                <label for="bebidas">Bebidas:</label>
                <select multiple name="bebida[]" id="bebida" class="form-select">
                  <?php foreach($bebidas_disponiveis as $bebida):?>
                    <option value="<?=$bebida["id"]?>"><?=$bebida["nome"]?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="from-group">
                <input type="submit" class="btn btn-primary" value="Fazer pedido" style="margin-top: 10px ;">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

<?php
include_once("templates/footer.php")
/*em cada select vamos fazer um foreach recebendo a variável que criamos no arquivo php que está fazendo comunicação com o 
banco de dados em cada option vamos usar como value a variável que representa a coluna que queremos manipular e passar o seus 
respectivos atributos (das colunas) */

/*abaixo do h2 temos um form com o método post, no action vamos linka-lo ao process/pizza.php, 
pq lá temos o else para quando for o método post, assim podemos fazer a nosso lógica para publicar o pedido*/
?>   