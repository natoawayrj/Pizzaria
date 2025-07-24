<?php
include_once("templates/header.php");

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    $_SESSION["msg"] = "Acesso restrito! Por favor, faça login como administrador.";
    $_SESSION["status"] = "danger";
    header("Location: admin_login.php"); 
    exit; 
}
include_once("process/conn.php");
try{
    $stmt_pedidos = $conn->prepare("
    SELECT
      p.id AS pedido_id,
      p.data_hora_pedido,
      s.tipo AS status_tipo,
      cl.nome AS cliente_nome,
      cl.endereco,
      cl.telefone,
      p.total
    FROM 
      pedidos p
    JOIN
      status s ON p.status_id = s.id
    JOIN
      clientes cl ON p.cliente_id = cl.id
    ORDER BY p.data_hora_pedido DESC");
    $stmt_pedidos->execute();
    $pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_ASSOC);

    // Para cada pedido, buscar detalhes adicionais (pizzas e bebidas)
    foreach ($pedidos as $key => $pedido) {
        // Busca as pizzas associadas a este pedido
        $stmt_pizzas = $conn->prepare("
            SELECT
                piz.id AS pizza_config_id,
                b.tipo AS borda_tipo,
                m.tipo AS massa_tipo
            FROM
                pizzas piz
            JOIN
                bordas b ON piz.borda_id = b.id
            JOIN
                massas m ON piz.massa_id = m.id
            WHERE
                piz.pedido_id = :pedido_id
        ");
        $stmt_pizzas->bindParam(":pedido_id", $pedido['pedido_id']);
        $stmt_pizzas->execute();
        $pedidos[$key]['pizzas'] = $stmt_pizzas->fetchAll(PDO::FETCH_ASSOC);

        // Para cada pizza, buscar seus sabores
        foreach ($pedidos[$key]['pizzas'] as $p_key => $pizza) {
            $stmt_sabores = $conn->prepare("
                SELECT
                    s.nome AS sabor_nome
                FROM
                    sabores_pizza sp
                JOIN
                    sabores s ON sp.sabor_id = s.id
                WHERE
                    sp.pizza_id = :pizza_id
            ");
            $stmt_sabores->bindParam(":pizza_id", $pizza['pizza_config_id']);
            $stmt_sabores->execute();
            $pedidos[$key]['pizzas'][$p_key]['sabores'] = $stmt_sabores->fetchAll(PDO::FETCH_ASSOC);
        }

        // Busca as bebidas associadas a este pedido
        $stmt_bebidas = $conn->prepare("
            SELECT
                b.nome AS bebida_nome,
                pb.quantidade
            FROM
                pedido_bebida pb
            JOIN
                bebidas b ON pb.bebida_id = b.id
            WHERE
                pb.pedido_id = :pedido_id
        ");
        $stmt_bebidas->bindParam(":pedido_id", $pedido['pedido_id']);
        $stmt_bebidas->execute();
        $pedidos[$key]['bebidas'] = $stmt_bebidas->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar todos os status disponíveis para o select
    $stmt_status = $conn->query("SELECT * FROM status")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao carregar pedidos: " . $e->getMessage();
    // Você pode adicionar uma mensagem de erro na sessão aqui, se desejar
}  
?>

<div class="container my-5">
    <h1 class="text-center mb-4">Gerenciar Pedidos</h1>

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?= $_SESSION['status'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msg']); unset($_SESSION['status']); ?>
    <?php endif; ?>

    <?php if (empty($pedidos)): ?>
        <p class="text-center">Nenhum pedido encontrado.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Data/Hora</th>
                        <th>Status</th>
                        <th>Pizzas</th>
                        <th>Bebidas</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?= $pedido['pedido_id'] ?></td>
                            <td><?= $pedido['cliente_nome'] ?></td>
                            <td><?= $pedido['telefone'] ?></td>
                            <td><?= $pedido['endereco'] ?></td>
                            <td><?= date('d/m/Y H:i:s', strtotime($pedido['data_hora_pedido'])) ?></td>
                            <td>
                                <form action="process/gerenciar_pedido.php" method="POST" class="d-flex">
                                    <input type="hidden" name="type" value="update_status">
                                    <input type="hidden" name="id" value="<?= $pedido['pedido_id'] ?>">
                                    <select name="status_id" class="form-select form-select-md me-2 status-select">
                                        <?php foreach($stmt_status as $status): ?>
                                            <option value="<?= $status['id'] ?>" <?= ($status['id'] == $pedido['status_tipo']) ? 'selected' : '' ?>>
                                                <?= $status['tipo'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-info">Atualizar</button>
                                </form>
                            </td>
                            <td>
                                <?php if (!empty($pedido['pizzas'])): ?>
                                    <ul class="list-unstyled">
                                        <?php foreach ($pedido['pizzas'] as $pizza): ?>
                                            <li>
                                                Massa: <?= $pizza['massa_tipo'] ?>,
                                                Borda: <?= $pizza['borda_tipo'] ?>,
                                                Sabores: <?= implode(', ', array_column($pizza['sabores'], 'sabor_nome')) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($pedido['bebidas'])): ?>
                                    <ul class="list-unstyled">
                                        <?php foreach ($pedido['bebidas'] as $bebida): ?>
                                            <li><?= $bebida['bebida_nome'] ?> (x<?= $bebida['quantidade'] ?>)</li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                            <td>
                                <form action="process/gerenciar_pedido.php" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar este pedido?');">
                                    <input type="hidden" name="type" value="delete">
                                    <input type="hidden" name="id" value="<?= $pedido['pedido_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Deletar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>


<?php 
include_once("templates/footer.php")
?>