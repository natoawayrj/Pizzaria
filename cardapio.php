<?php
  include_once("process/conn.php");
  include_once("templates/header.php");

  // Busca os sabores e bebidas do banco de dados
  $sabores = $conn->query("SELECT * FROM sabores;")->fetchAll(PDO::FETCH_ASSOC);
  $bebidas = $conn->query("SELECT * FROM bebidas;")->fetchAll(PDO::FETCH_ASSOC);

  // Mapeamento de nomes de sabores para nomes de arquivos de imagem
  // Certifique-se de que a chave 'tomate seco com rucula' está correta.
  $pizza_images = [
    'Calabresa' => 'calabresa.jpg',
    'Marguerita' => 'marguerita.jpg',
    'Portuguesa' => 'portuguesa.jpg',
    '4 Queijos' => 'queijos.jpg',
    'Frango com Catupiry' => 'frango.jpg',
    'Lombinho' => 'lombo.jpg',
    'tomate seco com rúcula' => 'tomateSeco.jpg',
    'Mussarela' => 'mussarela.jpg',
    'Champignon' => 'champignon.jpg',
    'Pepperoni' => 'pepperoni.jpg'
  ];

  // Mapeamento para as imagens das bebidas
  $bebida_images = [
      'Coca-cola' => 'coca2.jpg',
      'Pepsi' => 'pepsi2.jpg',
      'Guaraná Antartica' => 'guarana.jpg',
      'Heineken' => 'heineken2.jpg',
      'Brahma' => 'brahma.jpg',
      'Amstel' => 'amstel.jpg',
      'Fanta' => 'fanta.jpg',
      'Sprite' => 'sprit.jpg',
      'Spaten' => 'spaten.jpg',
      'Eisenbahn' => 'einsen.jpg'
  ];

?>
<div class="container my-5">
  <h1 class="text-center mb-5">Nosso Cardápio</h1>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
    <?php foreach ($sabores as $sabor): ?>
      <?php 
        // Remove espaços em branco do nome do sabor para uma correspondência mais segura
        $sabor_nome = trim($sabor['nome']);
        $imagem_pizza = isset($pizza_images[$sabor_nome]) ? $pizza_images[$sabor_nome] : 'pizza.jpg';
      ?>
      <div class="col">
        <div class="card h-100 shadow">
          <img src="img/<?= $imagem_pizza ?>" class="card-img-top" alt="<?= htmlspecialchars($sabor_nome) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($sabor_nome) ?></h5>
            <p class="card-text"><?= htmlspecialchars($sabor['descricao']) ?></p>
          </div>
          <div class="card-footer text-center">
            <strong>R$ <?= number_format($sabor['preco'], 2, ',', '.') ?></strong>
            <form action="process/add_to_cart.php" method="post" class="mt-2">
              <input type="hidden" name="id" value="sabor_<?= $sabor['id'] ?>">
              <input type="hidden" name="nome" value="<?= htmlspecialchars($sabor_nome) ?>">
              <input type="hidden" name="preco" value="<?= $sabor['preco'] ?>">
              <button type="submit" class="btn btn-warning">Adicionar ao Carrinho</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="container my-5">
  <h1 class="text-center mb-5">Bebidas</h1>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
    <?php foreach ($bebidas as $bebida): ?>
      <?php
        $bebida_nome = trim($bebida['nome']);
        $imagem_bebida = isset($bebida_images[$bebida_nome]) ? $bebida_images[$bebida_nome] : 'pizza.jpg'; 
      ?>
      <div class="col">
        <div class="card h-100 shadow">
          <img src="img/<?= $imagem_bebida ?>" class="card-img-top" alt="<?= htmlspecialchars($bebida_nome) ?>">
           <div class="card-body text-center">
            <h5 class="card-title"><?= htmlspecialchars($bebida_nome) ?></h5>
          </div>
          <div class="card-footer text-center">
            <strong>R$ <?= number_format($bebida['preco'], 2, ',', '.') ?></strong>
            <form action="process/add_to_cart.php" method="post" class="mt-2">
              <input type="hidden" name="id" value="bebida_<?= $bebida['id'] ?>">
              <input type="hidden" name="nome" value="<?= htmlspecialchars($bebida_nome) ?>">
              <input type="hidden" name="preco" value="<?= $bebida['preco'] ?>">
              <button type="submit" class="btn btn-warning">Adicionar ao Carrinho</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php
  include_once("templates/footer.php");
?>