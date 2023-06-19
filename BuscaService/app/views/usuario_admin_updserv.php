<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header e a classe de conexão com o banco de dados.
require_once 'layouts/site/header.php';
require_once "../database/conexao.php";

# verifica se existe sessão de usuario e se ele é administrador.
# se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
# sai da pagina.
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
    header("Location: index.php?error=Você não tem permissão para acessar esse recurso");
    exit;
}

# verifica se uma variavel id foi passada via GET 
$idserv = isset($_GET['idserv']) ? $_GET['idserv'] : 0;

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variaveis (nome, perfil, status) para armazenar os dados passados via método POST.
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : 0;

    # cria uma consulta banco de dados atualizando um usuario existente. 
    # usando como parametros os campos nome e password.
    $query = "UPDATE `busca_service`.`servico` SET `nome` = :nome, `categoria` = :categoria, `status` = :status 
                    WHERE idserv = :idserv";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':idserv', $idserv);

    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();

    # verifica se a quantidade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de update com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: usuario_admin_listserv.php?success=Serviço atualizado com sucesso!');
    } else {
        {
            $error_message = urlencode('Erro ao atualizar o serviço!');
            $redirect_url = 'usuario_admin_updserv.php?error=' . $error_message . '&idserv=' . $idserv;
            header('location: ' . $redirect_url);
        }
    }

    # destroi a conexao com o banco de dados.
    $dbh = null;
}

# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# filtrando pelo id do usuário.
$query = "SELECT * FROM `busca_service`.`servico` WHERE idserv=:idserv LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':idserv', $idserv);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();

# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$row = $stmt->fetch(PDO::FETCH_ASSOC);


# se o resultado retornado for igual a NULL, redireciona para a pagina de listar serviço.
# se não, cria a variavel row com dados do usuario selecionado.
if (!$row) {
    header('location: usuario_admin_listserv.php?error=Serviço inválido.');
}

# destroi a conexao com o banco de dados.
$dbh = null;
?>

<body>
    <?php require_once 'layouts/admin/menu.php'; ?>
    <main class="bg_form">
    <?php require_once "botoes_navegacao.php"?>
        <div class="main_opc">
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
            <section>
                <form action="" method="post" class="box">
                    <fieldset>
                        <legend><b>Atualizar Serviço</b></legend>

                        <div class="inputBox">
                            <input type="text" name="nome" id="nome" class="inputUser" autofocus value="<?= isset($row) ? $row['nome'] : '' ?>">
                            <label for="nome" class="labelInput">Nome do serviço:<span class="asterisk">*</span></label>
                        </div>

                        <div class="inputBox">
                            <input type="text" name="categoria" id="categoria" class="inputUser" autofocus value="<?= isset($row) ? $row['categoria'] : '' ?>">
                            <label for="categoria" class="labelInput">Categoria:<span class="asterisk">*</span></label>
                        </div><br>

                        <div class="inputBox">
                            <label for="status" class="labelInput">Status:<span class="asterisk">*</span></label>
                            <div class="select-wrapper">
                                <select name="status" id="status" class="inputUser" required><br><br>
                                    <option value="1" <?= isset($row) && $row['status'] == '1' ? 'selected' : '' ?>>Ativo
                                    </option>
                                    <option value="0" <?= isset($row) && $row['status'] == '0' ? 'selected' : '' ?>>Inativo
                                    </option>
                                </select>
                                <span class="select-icon"></span>
                            </div>
                        </div><br><br>

                    </fieldset>
                    <div class="btn_alinhamento">
                        <a href="usuario_admin.php">
                            <button type="submit" id="submit" value="Enviar" name="salvar">Enviar</button>
                        </a>
                        <a href="usuario_admin_listserv.php">
                            <button type="button" id="cancel" value="Cancelar" name="cancelar">Cancelar</button>
                        </a>
                    </div>
                </form>
            </section>
        </div>
        <br><br>
    </main>

</body>

</html>