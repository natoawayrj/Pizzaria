<?php
include_once("templates/header.php");
?>

<div id="main-banner2">
    <div id="carouselExampleAutoplaying" class="carousel slide h-100" data-bs-ride="carousel">
        
        <?php if (!isset($_SESSION['usuario']) && !isset($_SESSION['admin_logado'])): // CARROSSEL PARA USUÁRIO DESLOGADO ?>
            
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner h-100">
                <div class="carousel-item active">
                    <img src="img/romana.jpg" class="d-block w-100" alt="Pizza Romana com forno ao fundo">
                    <div class="carousel-caption ">
                        <h4>Faça o seu login</h4>
                        <p>Já tem uma conta? Acesse para fazer seu pedido rapidamente.</p>
                        <a href="./login.php" class="btn btn-primary">Login</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/marguerita.jpg" class="d-block w-100" alt="Pizza de Marguerita">
                    <div class="carousel-caption ">
                        <h4>Crie sua conta</h4>
                        <p>Cadastre-se para aproveitar nossas ofertas e pedir sua pizza favorita.</p>
                        <a href="./cadastro.php" class="btn btn-primary">Cadastrar</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/portuguesa.jpg" class="d-block w-100" alt="Pizza Portuguesa">
                    <div class="carousel-caption">
                        <h4>Conheça nosso Cardápio</h4>
                        <p>Uma variedade de sabores esperando por você.</p>
                        <a href="./cardapio.php" class="btn btn-primary">Ver Cardápio</a>
                    </div>
                </div>
            </div>

        <?php else: // CARROSSEL PARA USUÁRIO LOGADO (cliente ou admin) ?>

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner h-100">
                <div class="carousel-item active">
                    <img src="img/pepperoni.jpg" class="d-block w-100" alt="Pizza de Pepperoni">
                    <div class="carousel-caption">
                        <h4>Faça seu Pedido</h4>
                        <p>Monte sua pizza do jeito que você mais gosta.</p>
                        <a href="./dashboard.php" class="btn btn-success">Pedir Agora</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/queijos.jpg" class="d-block w-100" alt="Pizza de 4 Queijos">
                    <div class="carousel-caption">
                        <h4>Veja nosso Cardápio</h4>
                        <p>Confira todos os nossos sabores e bebidas.</p>
                        <a href="./cardapio.php" class="btn btn-success">Ver Cardápio</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/marguerita.jpg" class="d-block w-100" alt="Pizza de Lombo">
                    <div class="carousel-caption">
                        <h4>Sair da Conta</h4>
                        <p>Deseja encerrar a sua sessão?</p>
                        <a href="<?php echo isset($_SESSION['admin_logado']) ? 'admin_logout.php' : 'logout.php'; ?>" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>
</div>


<div class="container text-center my-5">
    <hr> <div class="row mt-5">
        <div class="col-md-6">
            <img src="img/pizza-over.jpg" alt="rodizio" class="img-fluid rounded">
        </div>
        <div class="col-md-6">
            <h3 class="mt-1">Jablonsk Pizzaria</h3>
            <h4 class="mt-5">Sabor italiano e alma brasiliera</h4>
            <p class="mt-5">A Pizzaria Jablonsk é o lugar ideal para você que busca uma experiência gastronômica completa e inesquecível.</p>
            <p class="mt-5">Nossas pizzas são preparadas com massas de longa fermentação, garantindo leveza, crocância e um sabor incomparável. Com ingredientes frescos e de alta qualidade, como a mozzarella di búfala, azeites aromáticos, criando sabores autênticos que remetem às melhores pizzarias de Nápoles..</p>
        </div>
        <div class="col-md-6">
            <h3 class="mt-5">Uma Viagem de Sabores Inesquecíveis</h3>
            <p class="mt-5">Explore um universo de sensações com nossa seleção incomparável de pizzas. São mais de 70 combinações de  sabores que vão desde as criações clássicas, preparadas com a mais pura tradição italiana, até as combinações mais audaciosas e contemporâneas, elaboradas para surpreender o seu paladar. Cada pizza é uma obra de arte, concebida com ingredientes frescos e selecionados, e uma massa de fermentação natural que garante leveza e sabor autêntico.</p>
            <p class="mt-3">O segredo da nossa qualidade reside no coração do nosso restaurante: o forno a lenha. É nele que a mágica acontece, assando cada pizza à perfeição. O calor intenso e a fumaça sutil da madeira conferem à massa uma textura única — crocante por fora, macia por dentro — e um aroma defumado que realça cada ingrediente. Convidamos você a se deliciar e descobrir por que, desde 2007, somos o destino certo para os verdadeiros amantes de pizza.</p>
        </div>
        <div class="col-md-6">
            <img src="img/pizza22.jpg" alt="rodizio" class="img-fluid rounded">
        </div>
    </div>
</div>


<?php
include_once("templates/footer.php");
?>