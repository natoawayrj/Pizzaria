<?php
  include_once("templates/header.php"); // Inclui o cabeçalho e inicia a sessão
?>
    <h1 class="text-center mt-3 mb-5">Acesso Administrativo</h1>

    <div class="container mt-5 " style="margin-bottom: 135px;">
     <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body">
            <h2 class="text-center mb-4">Login de Administrador</h2>
            <form action="process/logar_admin.php" method="POST">

              <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>

              <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-dark">Entrar como Admin</button>
              </div>

            </form>
          </div>
        </div>
       </div>
      </div>
    </div>

<?php
include_once("templates/footer.php"); // Inclui o rodapé
?>