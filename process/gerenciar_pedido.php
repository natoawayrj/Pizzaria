<?php
session_start();
include_once('conn.php'); // Conecta ao banco de dados

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    $type = $_POST["type"] ?? '';
    $pedidoId = $_POST["id"] ?? null;

    if (!$pedidoId) {
        $_SESSION["msg"] = "ID do pedido não fornecido.";
        $_SESSION["status"] = "danger";
        header("Location: ../gerenciar.php");
        exit;
    }

    if ($type === "update_status") {
        $statusId = $_POST["status_id"] ?? null;

        if (!$statusId) {
            $_SESSION["msg"] = "Status não selecionado para atualização.";
            $_SESSION["status"] = "danger";
            header("Location: ../gerenciar.php");
            exit;
        }

        try {
            $stmt = $conn->prepare("UPDATE pedidos SET status_id = :status_id WHERE id = :id");
            $stmt->bindParam(":status_id", $statusId);
            $stmt->bindParam(":id", $pedidoId);
            $stmt->execute();

            $_SESSION["msg"] = "Status do pedido #" . $pedidoId . " atualizado com sucesso!";
            $_SESSION["status"] = "success";

        } catch (PDOException $e) {
            $_SESSION["msg"] = "Erro ao atualizar status do pedido: " . $e->getMessage();
            $_SESSION["status"] = "danger";
            error_log("Erro ao atualizar status: " . $e->getMessage());
        }

    } elseif ($type === "delete") {
        try {
            $conn->beginTransaction();

            // 1. Deletar registros em 'sabores_pizza' associados às pizzas deste pedido
            $stmt_delete_sabores = $conn->prepare("DELETE sp FROM sabores_pizza sp JOIN pizzas p ON sp.pizza_id = p.id WHERE p.pedido_id = :pedido_id");
            $stmt_delete_sabores->bindParam(":pedido_id", $pedidoId);
            $stmt_delete_sabores->execute();

            // 2. Deletar registros em 'pedido_bebida' associados a este pedido
            $stmt_delete_bebidas = $conn->prepare("DELETE FROM pedido_bebida WHERE pedido_id = :pedido_id");
            $stmt_delete_bebidas->bindParam(":pedido_id", $pedidoId);
            $stmt_delete_bebidas->execute();
            
            // 3. Deletar registros em 'pedido_pizza' associados a este pedido
            $stmt_delete_pedido_pizza = $conn->prepare("DELETE FROM pedido_pizza WHERE pedido_id = :pedido_id");
            $stmt_delete_pedido_pizza->bindParam(":pedido_id", $pedidoId);
            $stmt_delete_pedido_pizza->execute();

            // 4. Deletar registros em 'pizzas' associados a este pedido
            $stmt_delete_pizzas = $conn->prepare("DELETE FROM pizzas WHERE pedido_id = :pedido_id");
            $stmt_delete_pizzas->bindParam(":pedido_id", $pedidoId);
            $stmt_delete_pizzas->execute();

            // 5. Deletar o pedido principal
            $stmt_delete_pedido = $conn->prepare("DELETE FROM pedidos WHERE id = :id");
            $stmt_delete_pedido->bindParam(":id", $pedidoId);
            $stmt_delete_pedido->execute();

            $conn->commit();

            $_SESSION["msg"] = "Pedido #" . $pedidoId . " deletado com sucesso!";
            $_SESSION["status"] = "success";

        } catch (PDOException $e) {
            $conn->rollBack();
            $_SESSION["msg"] = "Erro ao deletar pedido: " . $e->getMessage();
            $_SESSION["status"] = "danger";
            error_log("Erro ao deletar pedido: " . $e->getMessage());
        }
    }

    header("Location: ../gerenciar.php");
    exit;
}
?>