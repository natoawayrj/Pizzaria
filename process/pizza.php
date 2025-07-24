<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('conn.php'); // Conecta ao banco de dados

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {

    $data = $_POST;
    
    $clienteId = $_SESSION["cliente_id"] ?? null;
    $bordaId = $data["borda"] ?? null;
    $massaId = $data["massa"] ?? null;
    $sabores_selecionados = isset($data["sabores"]) ? $data["sabores"] : [];
    $bebidas_selecionadas = isset($data["bebida"]) ? (is_array($data["bebida"]) ? $data["bebida"] : [$data["bebida"]]) : [];

    // --- Validações ---
    if (empty($bordaId) || empty($massaId) || empty($sabores_selecionados)) {
        $_SESSION["msg"] = "Para montar sua pizza, você precisa selecionar a borda, a massa e pelo menos um sabor.";
        $_SESSION["status"] = "warning";
        header("Location: ../dashboard.php");
        exit;
    }

    if (count($sabores_selecionados) > 3) {
        $_SESSION["msg"] = "Você pode selecionar no máximo 3 sabores.";
        $_SESSION["status"] = "warning";
        header("Location: ../dashboard.php");
        exit;
    }

    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    try {
        // --- Processar e Adicionar a Pizza Personalizada ao Carrinho ---
        $pizza_preco_total = 0;
        $pizza_descricao_sabores = [];

        // Buscar detalhes da borda
        $stmt_borda = $conn->prepare("SELECT tipo, preco FROM bordas WHERE id = :id");
        $stmt_borda->bindParam(":id", $bordaId);
        $stmt_borda->execute();
        $borda = $stmt_borda->fetch(PDO::FETCH_ASSOC);
        $pizza_preco_total += $borda['preco'];
        $borda_nome = $borda['tipo'];

        // Buscar detalhes da massa
        $stmt_massa = $conn->prepare("SELECT tipo FROM massas WHERE id = :id");
        $stmt_massa->bindParam(":id", $massaId);
        $stmt_massa->execute();
        $massa_nome = $stmt_massa->fetchColumn();

        // Buscar e calcular o preço médio dos sabores
        $placeholders = implode(',', array_fill(0, count($sabores_selecionados), '?'));
        $stmt_sabores = $conn->prepare("SELECT nome, preco FROM sabores WHERE id IN ($placeholders)");
        $stmt_sabores->execute($sabores_selecionados);
        $sabores = $stmt_sabores->fetchAll(PDO::FETCH_ASSOC);

        $sabores_preco_total = 0;
        foreach ($sabores as $sabor) {
            $sabores_preco_total += $sabor['preco'];
            $pizza_descricao_sabores[] = $sabor['nome'];
        }
        $pizza_preco_total += ($sabores_preco_total / count($sabores));

        // Criar item da pizza para o carrinho
        $nome_pizza_personalizada = "Pizza Personalizada (" . implode(', ', $pizza_descricao_sabores) . ")";
        $descricao_completa = "Massa: {$massa_nome}, Borda: {$borda_nome}.";

        // Usamos uniqid para garantir que cada pizza personalizada seja um item único no carrinho
        $id_pizza_personalizada = uniqid('pizza_'); 

        $_SESSION['carrinho'][$id_pizza_personalizada] = [
            'nome' => $nome_pizza_personalizada,
            'descricao' => $descricao_completa, // campo extra para detalhes
            'preco' => $pizza_preco_total,
            'quantidade' => 1
        ];

        // --- Adicionar Bebidas ao Carrinho ---
        if (!empty($bebidas_selecionadas)) {
            $placeholders_bebidas = implode(',', array_fill(0, count($bebidas_selecionadas), '?'));
            $stmt_bebidas = $conn->prepare("SELECT id, nome, preco FROM bebidas WHERE id IN ($placeholders_bebidas)");
            $stmt_bebidas->execute($bebidas_selecionadas);
            $bebidas = $stmt_bebidas->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($bebidas as $bebida) {
                $id_bebida_carrinho = 'bebida_' . $bebida['id'];
                if (isset($_SESSION['carrinho'][$id_bebida_carrinho])) {
                    $_SESSION['carrinho'][$id_bebida_carrinho]['quantidade']++;
                } else {
                    $_SESSION['carrinho'][$id_bebida_carrinho] = [
                        'nome' => $bebida['nome'],
                        'preco' => $bebida['preco'],
                        'quantidade' => 1
                    ];
                }
            }
        }

        $_SESSION["msg"] = "Itens adicionados ao carrinho com sucesso!";
        $_SESSION["status"] = "success";
        header("Location: ../carrinho.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION["msg"] = "Erro ao processar seu pedido: " . $e->getMessage();
        $_SESSION["status"] = "danger";
        header("Location: ../dashboard.php");
        exit;
    }

} else {
    // Redireciona se o método não for POST
    header("Location: ../index.php");
    exit;
}
?>