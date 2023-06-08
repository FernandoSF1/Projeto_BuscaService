<?php
// Iniciando a sessão
session_start();

// Incluindo o arquivo de cabeçalho
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como cliente
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'PRO') {
    header("Location: index.php?error=Você precisa estar logado como profissional para ter acesso a este recurso");
    exit;
}

# usa o ID do profissional que ficou armazenado na sessão, após o login
$idpro = isset($_SESSION['usuario']['idpro']) ? $_SESSION['usuario']['idpro'] : 0;

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# filtrando pelo id do usuário.
$query = "SELECT * FROM `busca_service`.`profissional` WHERE idpro=:idpro LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':idpro', $idpro);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();

# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$row = $stmt->fetch(PDO::FETCH_ASSOC);


# se o resultado retornado for igual a NULL, redireciona para a pagina de listar usuario.
# se não, cria a variavel row com dados do usuario selecionado.
if (!$row) {
    header('location: index.php?error=Usuário inválido.');
}

# destroi a conexao com o banco de dados.
$dbh = null;
?>

<?php

    // Verifica se existe uma mensagem de sucesso enviada via GET
    if (isset($_GET['success'])) {
        $successMessage = $_GET['success'];
        $title = '';

        // Verifica o valor de $_GET['success'] para definir o título correspondente
        if ($successMessage === 'cliente') {
            $title = 'Cadastro de Cliente';
        } elseif ($successMessage === 'profissional') {
            $title = 'Cadastro de Profissional';
        } else {
            $title = 'Sucesso'; // Valor padrão para o título
        }

    ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '<?= $title ?>',
                text: '<?= $successMessage ?>',
            })
        </script>
    <?php
    }
    ?>

<body>
<div class="container">
    <h1 class="container_titulo">Perfil do Profissional</h1>

    <div class="dados">
        <div class="dados-pessoais_cli">
            <div class="campo campo-pessoal">
                <label for="nome" class="label_perfil">Nome:</label>
                <input type="text" id="nome" class="input_perfil" value="<?= $row['nome'] ?>" readonly>
            </div>

            <div class="campo campo-pessoal">
                <label for="titulo" class="label_perfil">Título do seu negócio:</label>
                <input type="text" id="titulo" class="input_perfil" value="<?= $row['titulo'] ?>" readonly>
            </div>
            

            <div class="campo campo-pessoal">
                <label for="email" class="label_perfil">E-mail:</label>
                <input type="text" id="email" class="input_perfil" value="<?= $row['email'] ?>" readonly>
            </div>

            <div class="campo campo-pessoal">
                <label for="cpf" class="label_perfil">CPF:</label>
                <input type="text" id="cpf" class="input_perfil" value="<?= $row['cpf'] ?>" readonly>
            </div>

            <div class="campo campo-pessoal">
                <label for="telefone" class="label_perfil">Celular:</label>
                <input type="text" id="telefone" class="input_perfil" value="<?= $row['telefone'] ?>" readonly>
            </div>

            <div class="campo campo-pessoal">
                <label for="telefone2" class="label_perfil">Celular:</label>
                <input type="text" id="telefone2" class="input_perfil" value="<?= $row['telefone2'] ?>" readonly>
            </div>


            <div class="campo campo-pessoal">
                <label for="dataregcli" class="label_perfil">Data de Registro:</label>
                <input type="text" id="data_registro" class="input_perfil" value="<?= date('d/m/Y', strtotime($row['dataregpro'])) ?>" readonly>
            </div>
        </div>

        <div class="dados dados-endereco">
            <div class="campo campo-endereco">
                <label for="cep" class="label_perfil">CEP:</label>
                <input type="text" id="cep" class="input_perfil" value="<?= $row['cep'] ?>" readonly>
            </div>

            <div class="campo campo-endereco">
                <label for="estado" class="label_perfil">Estado:</label>
                <input type="text" id="estado" class="input_perfil" value="<?= $row['estado'] ?>" readonly>
            </div>

            <div class="campo campo-endereco">
                <label for="cidade" class="label_perfil">Cidade:</label>
                <input type="text" id="cidade" class="input_perfil" value="<?= $row['cidade'] ?>" readonly>
            </div>

            <div class="campo campo-endereco">
                <label for="bairro" class="label_perfil">Bairro:</label>
                <input type="text" id="bairro" class="input_perfil" value="<?= $row['bairro'] ?>" readonly>
            </div>
        </div>
    </div>

    <a href="update_pro.php?idpro=<?= $row['idpro'] ?>" class="btn" id="edit">Editar dados</a>&nbsp;
</div>
</main>

<!--INÍCIO DOBRA RODAPÉ-->

<!-- inclui o arquivo de rodape do site -->
<?php require_once 'layouts/site/footer.php'; ?>