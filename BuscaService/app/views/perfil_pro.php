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
$query = "SELECT p.*, s.nome AS nome_servico
          FROM `busca_service`.`profissional` p
          JOIN `busca_service`.`profissional_has_servico` ps ON p.idpro = ps.idpro
          JOIN `busca_service`.`servico` s ON ps.idserv = s.idserv
          WHERE p.idpro = :idpro
          LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':idpro', $idpro);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();

# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//echo '<pre>';var_dump($row);exit;

# se o resultado retornado for igual a NULL, redireciona para a pagina de listar usuario.
# se não, cria a variavel row com dados do usuario selecionado.
if (!$row) {
    header('location: index.php?error=Usuário inválido.');
}

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # recupera o id do enviado por post para delete ou update.
    $idpro = (isset($_POST['idpro']) ? $_POST['idpro'] : 0);
    $operacao = (isset($_POST['botao']) ? $_POST['botao'] : null);
    # verifica se o nome do botão acionado por post se é deletar ou atualizar
    if ($operacao === 'deletar') {
        # cria uma query no banco de dados para excluir o usuario com id informado 
        $query = "DELETE FROM `busca_service`.`profissional` WHERE idpro = :idpro";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idpro', $idpro);

        # executa a consulta banco de dados para excluir o registro.
        $stmt->execute();

        # verifica se a quantiade de registros excluido é maior que zero.
        # se sim, redireciona para a pagina index com mensagem de sucesso.
        # se não, redireciona para a pagina perfil_pro com mensagem de erro.
        if ($stmt->rowCount()) {
            # destroi todas sessões, se existirem.
            session_destroy();
            header('location: index.php?success=Conta excluída com sucesso!');
        } else {
            header('location: perfil_pro.php?error=Erro ao excluir a sua conta!');
        }
    }
}

# destroi a conexao com o banco de dados.
$dbh = null;
?>

<?php
# Verifica se existe uma mensagem de erro enviada via GET
if (isset($_GET['error'])) {
?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Ops!',
            text: '<?= $_GET['error'] ?>',
        });
    </script>
<?php
}
# Verifica se existe uma mensagem de sucesso enviada via GET
elseif (isset($_GET['success'])) {
?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: '<?= $_GET['success'] ?>',
        });
    </script>
<?php
}
?>

<body>
<?php require_once "botoes_navegacao.php"?>
    <div class="container">
        <h1 class="container_titulo">Perfil do Profissional</h1>

        <!-- Botão: Editar dados -->
        <a href="update_pro.php?idpro=<?= base64_encode($row['idpro']) ?>">
            <button class="perfil-btn" name="botao" id="edit-perfil" value="editar">Editar meus dados</button>
        </a>

        <!-- Botão: Apagar conta -->
        <form action="" method="post">
            <input type="hidden" name="idpro" value="<?= $row['idpro'] ?>" />
            <button class="perfil-btn" name="botao" id="delete-perfil" value="deletar" onclick="return confirm('Deseja realmente excluir sua conta?');">Excluir minha conta</button>
        </form>

        <div class="dados">
            <div class="dados-pessoais_cli">

                <div class="campo campo-pessoal">
                    <label for="" class="label_perfil">Imagem do perfil:</label>
                    <img src="<?= $row['fotoprin'] ?>" class="imagens_perfil" alt="Imagem perfil">
                </div>

                <div class="campo campo-pessoal">
                    <label for="nome" class="label_perfil">Nome do seu negócio:</label>
                    <p class="dado_pessoal_perfil"><?= $row['titulo'] ?></p>
                </div>

                <div class="campo campo-pessoal">
                    <label for="nome" class="label_perfil">Seu nome:</label>
                    <p class="dado_pessoal_perfil"><?= $row['nome'] ?></p>
                </div>

                <div class="campo campo-pessoal">
                    <label for="email" class="label_perfil">E-mail:</label>
                    <p class="dado_pessoal_perfil"><?= $row['email'] ?></p>
                </div>

                <div class="campo campo-pessoal">
                    <label for="cpf" class="label_perfil">CPF:</label>
                    <p class="dado_pessoal_perfil"><?= $row['cpf'] ?></p>
                </div>

                <div class="campo campo-pessoal">
                    <label for="nome_servico" class="label_perfil">Serviço(s) que você está oferecendo:</label>
                    <p class="dado_pessoal_perfil servicos-oferecidos-perfil">
                        <?php
                        $dbh = Conexao::getInstance();
                        $queryServicos = "SELECT s.nome FROM servico s INNER JOIN profissional_has_servico ps ON s.idserv = ps.idserv WHERE ps.idpro = :idpro ORDER BY s.nome ASC";
                        $stmtServicos = $dbh->prepare($queryServicos);
                        $stmtServicos->bindParam(':idpro', $idpro);
                        $stmtServicos->execute();
                        $servicos = $stmtServicos->fetchAll(PDO::FETCH_COLUMN);
                        $totalServicos = count($servicos);
                        foreach ($servicos as $index => $servico) {
                            echo '<span class="destaque-servico">' . $servico . '</span>';
                            if ($index < $totalServicos - 1) {
                                echo ', ';
                            }
                        }
                        ?>
                    </p>
                </div>

                <div class="campo campo-pessoal">
                    <h2 class="descricao_titulo">Descrição sobre <span class="titulo_descricao_perfil"><?php echo "&nbsp;" . $row['titulo']; ?></span>:</h2>
                    <div class="descricao_negocio">
                        <p><?php echo $row['descricaonegocio']; ?></p>
                    </div>
                </div>
            </div>

            <div class="dados dados-endereco">
                <div class="campo campo-endereco">
                    <label for="cep" class="label_perfil">CEP:</label>
                    <p class="dado_pessoal_perfil"><?= $row['cep'] ?></p>
                </div>

                <div class="campo campo-endereco">
                    <label for="estado" class="label_perfil">Estado:</label>
                    <p class="dado_pessoal_perfil"><?= $row['estado'] ?></p>
                </div>

                <div class="campo campo-endereco">
                    <label for="cidade" class="label_perfil">Cidade:</label>
                    <p class="dado_pessoal_perfil"><?= $row['cidade'] ?></p>
                </div>

                <div class="campo campo-endereco">
                    <label for="bairro" class="label_perfil">Bairro:</label>
                    <p class="dado_pessoal_perfil"><?= $row['bairro'] ?></p>
                </div>

                <div class="campo campo-pessoal">
                    <label for="telefone" class="label_perfil">Telefones de Contato:</label>
                    <input type="text" id="telefone" class="input_perfil" value="<?= $row['telefone'] ?>" readonly>
                </div>

                <div class="campo campo-pessoal">
                    <input type="text" id="telefone2" class="input_perfil" value="<?= $row['telefone2'] ?>" readonly>
                </div>

                <div class="campo campo-pessoal">
                    <label for="dataregcli" class="label_perfil">Data de cadastro:</label>
                    <p class="dado_pessoal_perfil"><?= date('d/m/Y', strtotime($row['dataregpro'])) ?></p>
                </div>
            </div>
        </div>

        <section class="imagens_trabalho">
            <h2 class="imagens_trabalho_titulo">Galeria de imagens</h2>
            <div class="imagens_trabalho_img">
                <?php if (!empty($row['fotosec']) || !empty($row['fotosec2'])) : ?>
                    <?php if (!empty($row['fotosec'])) : ?>
                        <div class="campo campo-pessoal">
                            <img src="<?= $row['fotosec'] ?>" class="imagens_perfil_trabalho" alt="imagem" style="width:230px;height:220px;">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($row['fotosec2'])) : ?>
                        <div class="campo campo-pessoal">
                            <img src="<?= $row['fotosec2'] ?>" class="imagens_perfil_trabalho" alt="Imagem" style="width:230px;height:220px;">
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="nao-encontrados-img">
                        <p>Você não cadastrou imagens do seu serviço</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
    </main>

    <!--INÍCIO DOBRA RODAPÉ-->

    <!-- inclui o arquivo de rodape do site -->
    <?php require_once 'layouts/site/footer.php'; ?>