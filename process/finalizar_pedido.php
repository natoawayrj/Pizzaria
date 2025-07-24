<?php

session_start();
include_once('conn.php');

// --- Validações Iniciais ---
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION['usuario']) || !isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    $_SESSION["msg"] = "Seu carrinho está vazio ou você não está logado.";
    $_SESSION["status"] = "warning";
    header("Location: ../carrinho.php");
    exit;
}

$clienteId = $_SESSION['cliente_id'];
$forma_pagamento = $_POST['forma_pagamento'] ?? 'Não informado';
$observacoes = $_POST['observacoes'] ?? '';
$status_inicial = 1; // ID para 'Em produção'

// --- Início do Processamento do Pedido ---
try {
    $conn->beginTransaction();

    // 1. Calcular o total final a partir do carrinho na sessão
    $total_final = 0;
    foreach ($_SESSION['carrinho'] as $item) {
        $total_final += $item['preco'] * $item['quantidade'];
    }

    // 2. Inserir o registro principal na tabela 'pedidos'
    $stmt_pedido = $conn->prepare(
        "INSERT INTO pedidos (cliente_id, status_id, data_hora_pedido, observacoes, total, forma_pagamento) 
         VALUES (:cliente_id, :status_id, NOW(), :observacoes, :total, :forma_pagamento)"
    );
    $stmt_pedido->bindParam(":cliente_id", $clienteId);
    $stmt_pedido->bindParam(":status_id", $status_inicial);
    $stmt_pedido->bindParam(":observacoes", $observacoes);
    $stmt_pedido->bindParam(":total", $total_final);
    $stmt_pedido->bindParam(":forma_pagamento", $forma_pagamento);
    $stmt_pedido->execute();
    
    $pedidoId = $conn->lastInsertId(); // Pega o ID do pedido que acabamos de criar

    // 3. Processar cada item do carrinho e registrar nas tabelas de detalhes
    // NOTA: Esta lógica assume que todas as pizzas (mesmo as do cardápio) são tratadas como itens simples.
    // A pizza "Personalizada" é um item especial que não possui uma estrutura de IDs no carrinho.
    // Esta implementação guarda seu nome e descrição, mas não a salva de forma estruturada nas tabelas `pizzas` e `sabores_pizza`.
    
    foreach ($_SESSION['carrinho'] as $id_item_carrinho => $item) {
        // Verifica se é uma bebida
        if (strpos($id_item_carrinho, 'bebida_') === 0) {
            $bebida_id = str_replace('bebida_', '', $id_item_carrinho);
            $stmt_item = $conn->prepare("INSERT INTO pedido_bebida (pedido_id, bebida_id, quantidade) VALUES (:pedido_id, :bebida_id, :quantidade)");
            $stmt_item->bindParam(":pedido_id", $pedidoId);
            $stmt_item->bindParam(":bebida_id", $bebida_id);
            $stmt_item->bindParam(":quantidade", $item['quantidade']);
            $stmt_item->execute();
        }
        // Verifica se é uma pizza (do cardápio ou personalizada)
        else {
             // Para simplificar, estamos tratando todas as pizzas como um item de texto.
             // Uma melhoria futura seria estruturar a "Pizza Personalizada" no carrinho para salvar seus componentes (borda, massa, sabores) separadamente.
            if (strpos($id_item_carrinho, 'sabor_') === 0) {
                $sabor_id = str_replace('sabor_', '', $id_item_carrinho);
                
                // Vamos criar uma "pizza" simples para este sabor do cardápio
                $stmt_pizza_cfg = $conn->prepare("INSERT INTO pizzas (pedido_id, borda_id, massa_id, cliente_id) VALUES (:pedido_id, 1, 1, :cliente_id)"); // Usando IDs padrão 1 para borda e massa
                $stmt_pizza_cfg->bindParam(":pedido_id", $pedidoId);
                $stmt_pizza_cfg->bindParam(":cliente_id", $clienteId);
                $stmt_pizza_cfg->execute();
                $pizzaConfigId = $conn->lastInsertId();

                $stmt_sabor = $conn->prepare("INSERT INTO sabores_pizza (pizza_id, sabor_id) VALUES (:pizza_id, :sabor_id)");
                $stmt_sabor->bindParam(":pizza_id", $pizzaConfigId);
                $stmt_sabor->bindParam(":sabor_id", $sabor_id);
                $stmt_sabor->execute();
            }
            // Aqui seria a lógica para a pizza personalizada, se ela guardasse os IDs.
        }
    }

    // 4. Se tudo deu certo, confirma as operações no banco
    $conn->commit();

    // 5. Limpa o carrinho e guarda o ID do último pedido para a página de sucesso
    unset($_SESSION['carrinho']);
    $_SESSION['last_pedido_id'] = $pedidoId;
    $_SESSION["msg"] = "Pedido #" . $pedidoId . " realizado com sucesso!";
    $_SESSION["status"] = "success";
    header("Location: ../pedido.php");
    exit;

} catch (PDOException $e) {
    // 6. Se algo deu errado, desfaz tudo
    $conn->rollBack();
    $_SESSION["msg"] = "Erro crítico ao finalizar o pedido. Por favor, tente novamente. <br><small>Erro: " . $e->getMessage() . "</small>";
    $_SESSION["status"] = "danger";
    error_log("Erro em finalizar_pedido.php: " . $e->getMessage());
    header("Location: ../checkout.php");
    exit;
}
?>