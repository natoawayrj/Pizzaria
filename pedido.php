<?php
include_once("templates/header.php"); // Inclui o cabeçalho e inicia a sessão

// Verifica se o ID do último pedido está na sessão
if (!isset($_SESSION['last_pedido_id']) || !isset($_SESSION['cliente_id'])) {
    $_SESSION["msg"] = "Nenhum pedido recente encontrado ou você não está logado.";
    $_SESSION["status"] = "danger";
    header("Location: index.php");
    exit;
}

$pedidoId = $_SESSION['last_pedido_id'];
$clienteId = $_SESSION['cliente_id'];

// Limpa o ID do último pedido da sessão para evitar que ele seja exibido novamente por engano
unset($_SESSION['last_pedido_id']);

// Busca os detalhes do pedido e da pizza no banco de dados
try {
    // Busca o pedido principal
    $stmt_pedido = $conn->prepare("
        SELECT
            p.id AS pedido_id,
            p.data_hora_pedido,
            s.tipo AS status_tipo,
            cl.nome AS cliente_nome,
            cl.endereco,
            cl.telefone,
            p.observacoes,
            p.total
        FROM
            pedidos p
        JOIN
            status s ON p.status_id = s.id
        JOIN
            clientes cl ON p.cliente_id = cl.id
        WHERE
            p.id = :pedido_id AND p.cliente_id = :cliente_id
    ");
    $stmt_pedido->bindParam(":pedido_id", $pedidoId);
    $stmt_pedido->bindParam(":cliente_id", $clienteId);
    $stmt_pedido->execute();
    $pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        $_SESSION["msg"] = "Detalhes do pedido não encontrados ou não pertencem a você.";
        $_SESSION["status"] = "danger";
        header("Location: index.php");
        exit;
    }

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
    $stmt_pizzas->bindParam(":pedido_id", $pedidoId);
    $stmt_pizzas->execute();
    $pizzas_do_pedido = $stmt_pizzas->fetchAll(PDO::FETCH_ASSOC);

    // Para cada pizza, busca seus sabores
    foreach ($pizzas_do_pedido as $key => $pizza) {
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
        $pizzas_do_pedido[$key]['sabores'] = $stmt_sabores->fetchAll(PDO::FETCH_ASSOC);
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
    $stmt_bebidas->bindParam(":pedido_id", $pedidoId);
    $stmt_bebidas->execute();
    $bebidas_do_pedido = $stmt_bebidas->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $_SESSION["msg"] = "Erro ao carregar detalhes do pedido: " . $e->getMessage();
    $_SESSION["status"] = "danger";
    error_log("Erro ao carregar pedido_confirmado: " . $e->getMessage());
    header("Location: index.php");
    exit;
}

?>

<div class="container my-5 pedido-info">
    <h1 class="text-center mb-4">Pedido Realizado com Sucesso!</h1>
    <p class="text-center lead">Seu pedido #<?= $pedido['pedido_id'] ?> foi enviado e está em **<?= $pedido['status_tipo'] ?>**.</p>
    <p class="text-center">Aguarde a entrega! Em breve você receberá mais informações.</p>

    <div class="card shadow mt-5">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Detalhes do Pedido #<?= $pedido['pedido_id'] ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> <?= $pedido['cliente_nome'] ?></p>
            <p><strong>Endereço de Entrega:</strong> <?= $pedido['endereco'] ?></p>
            <p><strong>Telefone:</strong> <?= $pedido['telefone'] ?></p>
            <p><strong>Data/Hora do Pedido:</strong> <?= date('d/m/Y H:i:s', strtotime($pedido['data_hora_pedido'])) ?></p>
            <p><strong>Status Atual:</strong> <?= $pedido['status_tipo'] ?></p>
            <p><strong>Total Estimado:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>
            <?php if (!empty($pedido['observacoes'])): ?>
                <p><strong>Observações:</strong> <?= $pedido['observacoes'] ?></p>
            <?php endif; ?>

            <hr>

            <h5>Pizzas do Pedido:</h5>
            <?php if (!empty($pizzas_do_pedido)): ?>
                <ul class="list-group mb-3">
                    <?php foreach ($pizzas_do_pedido as $pizza): ?>
                        <li class="list-group-item">
                            <strong>Massa:</strong> <?= $pizza['massa_tipo'] ?><br>
                            <strong>Borda:</strong> <?= $pizza['borda_tipo'] ?><br>
                            <strong>Sabores:</strong>
                            <?php if (!empty($pizza['sabores'])): ?>
                                <?= implode(', ', array_column($pizza['sabores'], 'sabor_nome')) ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhuma pizza neste pedido.</p>
            <?php endif; ?>

            <h5>Bebidas do Pedido:</h5>
            <?php if (!empty($bebidas_do_pedido)): ?>
                <ul class="list-group">
                    <?php foreach ($bebidas_do_pedido as $bebida): ?>
                        <li class="list-group-item">
                            <?= $bebida['bebida_nome'] ?> (Quantidade: <?= $bebida['quantidade'] ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhuma bebida neste pedido.</p>
            <?php endif; ?>
        </div>
        <div class="card-footer text-center">
            <a href="dashboard.php" class="btn btn-primary">Fazer outro pedido</a>
            <a href="index.php" class="btn btn-secondary">Voltar para Home</a>
        </div>
    </div>
</div>

<?php
include_once("templates/footer.php"); 
?>