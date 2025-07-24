<?php

  include_once("templates/header.php");
  include_once("process/cadastrar.php");
?>
    <h1 class='cadastro text-center mt-4'>Cadastro</h1>
    <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body">
            <h2 class="text-center mb-4">Cadastro de Cliente</h2>
            <form action="cadastro.php" method="POST">
              
              <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
              </div>

              <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input type="tel" class="form-control" id="telefone" name="telefone" required>
              </div>

              <div class="mb-3">
                <label for="endereco" class="form-label">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              
              <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-dark">Cadastrar</button>
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