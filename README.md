Pizzaria Jablonsk - Projeto Web
Olá! 👋 Este é o repositório do meu projeto de uma pizzaria online, a "Jablonsk Pizzaria". Desenvolvi este site como parte dos meus estudos em desenvolvimento web, aplicando conceitos de back-end com PHP e front-end com Bootstrap. Foi um grande aprendizado!

📜 Descrição
Este projeto é um sistema completo para uma pizzaria fictícia. Ele permite que clientes se cadastrem, façam login, montem suas próprias pizzas ou escolham sabores do cardápio, adicionem itens ao carrinho e finalizem seus pedidos. Além disso, há uma área administrativa para gerenciar os pedidos recebidos.

O objetivo principal foi criar uma aplicação web funcional do zero, passando por todas as etapas: desde a estruturação do banco de dados até a criação das interfaces para o cliente e para o administrador.

✨ Funcionalidades
O sistema possui duas áreas principais: a do cliente e a do administrador.

Para Clientes:
Cadastro e Login: Os clientes podem criar uma conta e fazer login para uma experiência personalizada.

Cardápio Completo: Visualização de todas as pizzas e bebidas disponíveis.

Monte sua Pizza: Uma funcionalidade que permite ao cliente escolher a massa, a borda e até três sabores para criar uma pizza personalizada.

Carrinho de Compras: Adicione pizzas prontas, bebidas ou a sua pizza personalizada ao carrinho. É possível também remover itens ou limpar o carrinho.

Checkout: Um processo simples para revisar o pedido, confirmar o endereço e escolher a forma de pagamento.

Confirmação do Pedido: Após finalizar a compra, o cliente vê uma tela com todos os detalhes do seu pedido.

Para Administradores:
Login Seguro: Uma tela de login exclusiva para os administradores do sistema.

Dashboard de Gerenciamento: Uma visão geral de todos os pedidos realizados, com informações detalhadas de cada um.

Atualização de Status: O admin pode atualizar o andamento de um pedido (Ex: "Em produção", "Saiu para entrega", "Finalizado").

Cancelamento de Pedidos: É possível remover um pedido do sistema.

🚀 Tecnologias Utilizadas
Para construir este projeto, utilizei as seguintes tecnologias:

Back-end:

PHP: Para toda a lógica do servidor, manipulação de dados e gerenciamento de sessões.

PDO (PHP Data Objects): Para fazer a conexão segura com o banco de dados.

Front-end:

HTML5: Para a estrutura das páginas.

CSS3: Para a estilização personalizada.

Bootstrap 5: Para criar um layout responsivo e moderno de forma rápida.

Banco de Dados:

MySQL: Para armazenar as informações dos clientes, produtos, pedidos e etc (inferido a partir do arquivo de conexão).

💻 Como Executar o Projeto
Se você quiser testar este projeto na sua máquina local, aqui estão os passos:

Ambiente Local: Certifique-se de ter um ambiente de servidor local como XAMPP, WAMP ou MAMP instalado e rodando.

Clone o Repositório:

Bash

git clone https://github.com/natoawayrj/Pizzaria.git


Banco de Dados:

Crie um banco de dados chamado pizzaria no seu MySQL (via phpMyAdmin).

Você precisará criar as tabelas (clientes, pedidos, pizzas, sabores, etc.). A estrutura pode ser entendida analisando os arquivos .php na pasta process/ e no gerenciar.php.

Conexão com o Banco: Verifique o arquivo process/conn.php e, se necessário, altere as variáveis $user, $pass, $db e $host com as suas credenciais do banco de dados.

Acesse no Navegador: Abra seu navegador e acesse http://localhost/pizzaria/.

🧠 O que Aprendi com Este Projeto
Como desenvolvedor júnior, este projeto foi uma jornada incrível de aprendizado. Alguns dos pontos que mais me desenvolveram foram:

Operações CRUD: Consegui praticar e entender de verdade como criar, ler, atualizar e deletar dados de um banco de dados usando PHP e PDO.

Gerenciamento de Sessões ($_SESSION): Aprender a controlar o estado do usuário (logado ou não) e a manter os itens no carrinho de compras foi um desafio muito legal.

Estrutura de um Projeto PHP: Aprendi na prática a importância de organizar o código, separando a lógica de processamento (/process), as páginas de visualização (/templates) e os arquivos principais.

Lógica de Negócio: Pensar em como as funcionalidades deveriam interagir (como o preço de uma pizza com múltiplos sabores é calculado, por exemplo) me deu uma nova perspectiva sobre desenvolvimento.

Front-end com Bootstrap: Foi ótimo para ganhar agilidade na criação de interfaces que funcionam bem tanto no desktop quanto no celular.

Ainda há muito a melhorar e a aprender, mas estou muito orgulhoso do resultado. Fique à vontade para explorar o código, dar sugestões ou até mesmo apontar erros. Todo feedback é bem-vindo! 😄
