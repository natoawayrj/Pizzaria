<?php
include_once("templates/header.php");

// Se o carrinho estiver vazio ou o usuário não estiver logado, não tem por que estar aqui.
if (!isset($_SESSION['usuario']) || !isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header("Location: index.php");
    exit;
}

// Buscar dados do cliente para exibir o endereço de entrega
$clienteId = $_SESSION['cliente_id'];
$stmt_cliente = $conn->prepare("SELECT nome, endereco, telefone FROM clientes WHERE id = :id");
$stmt_cliente->bindParam(":id", $clienteId);
$stmt_cliente->execute();
$cliente = $stmt_cliente->fetch(PDO::FETCH_ASSOC);

// Calcular o total final do carrinho
$total_carrinho = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total_carrinho += $item['preco'] * $item['quantidade'];
}
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Finalizar Pedido</h1>
    <p class="text-center lead">Revise os detalhes do seu pedido e escolha a forma de pagamento.</p>

    <div class="row g-5 mt-3">
        <div class="col-md-6">
            <h4>Resumo do seu Carrinho</h4>
            <ul class="list-group mb-3">
                <?php foreach ($_SESSION['carrinho'] as $item): ?>
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-0"><?= htmlspecialchars($item['nome']) ?> (x<?= $item['quantidade'] ?>)</h6>
                            <?php if (isset($item['descricao'])): ?>
                                <small class="text-muted"><?= htmlspecialchars($item['descricao']) ?></small>
                            <?php endif; ?>
                        </div>
                        <span class="text-muted">R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></span>
                    </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between">
                    <span><strong>Total (R$)</strong></span>
                    <strong>R$ <?= number_format($total_carrinho, 2, ',', '.') ?></strong>
                </li>
            </ul>
        </div>

        <div class="col-md-6">
            <h4>Detalhes da Entrega e Pagamento</h4>
            <div class="card mb-4">
                <div class="card-body">
                    <p><strong>Nome:</strong> <?= htmlspecialchars($cliente['nome']) ?></p>
                    <p><strong>Telefone:</strong> <?= htmlspecialchars($cliente['telefone']) ?></p>
                    <p class="mb-0"><strong>Endereço de Entrega:</strong><br><?= htmlspecialchars($cliente['endereco']) ?></p>
                </div>
            </div>

            <form action="process/finalizar_pedido.php" method="POST">
                
                <h4 class="mb-3">Forma de Pagamento</h4>
                <div class="my-3">
                    <div class="form-check">
                        <input id="dinheiro" name="forma_pagamento" type="radio" class="form-check-input" value="Dinheiro" required>
                        <label class="form-check-label" for="dinheiro">Dinheiro</label>
                    </div>
                    <div class="form-check">
                        <input id="pix" name="forma_pagamento" type="radio" class="form-check-input" value="Pix" required>
                        <label class="form-check-label" for="pix">Pix</label>
                    </div>
                    <div class="form-check">
                        <input id="cartao" name="forma_pagamento" type="radio" class="form-check-input" value="Cartão na Entrega" required>
                        <label class="form-check-label" for="cartao">Cartão de Crédito/Débito (na entrega)</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observações do Pedido:</label>
                    <textarea class="form-control" name="observacoes" id="observacoes" rows="3" placeholder="Ex: Sem cebola, troco para R$ 50, etc."></textarea>
                </div>

                <hr class="my-4">

                <button class="w-100 btn btn-primary btn-lg" type="submit">Confirmar e Fazer Pedido</button>
            </form>
        </div>
    </div>
</div>

<?php
include_once("templates/footer.php");
?>