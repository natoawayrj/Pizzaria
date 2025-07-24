<?php
include_once("templates/header.php");

$total = 0;
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Seu Carrinho de Compras</h1>

    <?php if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrinho'] as $id => $item): ?>
                    <?php
                        $subtotal = $item['preco'] * $item['quantidade'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        <td>
                            <form action="process/remove_from_cart.php" method="post">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <a href="checkout.php" class="btn btn-primary">Finalizar Pedido</a>
            <a href="process/clear_cart.php" class="btn btn-secondary">Limpar Carrinho</a>
        </div>

    <?php else: ?>
        <p class="text-center">Seu carrinho está vazio.</p>
        <div class="text-center">
            <a href="cardapio.php" class="btn btn-primary mt-4">Ver Cardápio</a>
        </div>
    <?php endif; ?>
</div>

<?php
include_once("templates/footer.php");
?>