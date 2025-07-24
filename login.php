<?php


include_once("templates/header.php");
?>

<h1 class="text-center mt-4 mb-5">Faça o seu login</h1>

<div class="container mt-8" style="margin-bottom: 130px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center mb-4">Login</h2>

                    <?php
                        // (NOVO) Bloco para exibir a mensagem de erro
                        if (isset($_SESSION['login_error'])) {
                            // Exibe o erro em um alerta do Bootstrap
                            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
                            
                            // Limpa o erro da sessão para não mostrar de novo ao recarregar a página
                            unset($_SESSION['login_error']);
                        }
                    ?>

                    <form action="process/logar.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once("templates/footer.php")
?>