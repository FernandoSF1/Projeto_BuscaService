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
$idcli = isset($_GET['idcli']) ? $_GET['idcli'] : 0;

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria variaveis (nome, perfil, status) para armazenar os dados passados via método POST.
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone-whatsapp']) ? $_POST['telefone-whatsapp'] : '';
    $cep = isset($_POST['cep']) ? $_POST['cep'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';
    $bairro = isset($_POST['bairro']) ? $_POST['bairro'] : '';
    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : 0;

    # cria uma consulta banco de dados atualizando um usuario existente. 
    # usando como parametros os campos nome e password.
    $query = "UPDATE `busca_service`.`cliente` SET `nome` = :nome, `email` = :email, `telefone` = :telefone, `cep` = :cep, `estado` = :estado, `cidade` = :cidade, `bairro` = :bairro, `perfil` = :perfil, `status` = :status 
                    WHERE idcli = :idcli";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':perfil', $perfil);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':idcli', $idcli);

    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();

    # verifica se a quantidade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de admin com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: usuario_admin_listcli.php?success=Cliente atualizado com sucesso!');
    } else {
        {
            $error_message = urlencode('Erro ao atualizar o cliente!');
            $redirect_url = 'usuario_admin_updcli.php?error=' . $error_message . '&idcli=' . $idcli;
            header('location: ' . $redirect_url);
        }
    }

    # destroi a conexao com o banco de dados.
    $dbh = null;
}

# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# filtrando pelo id do usuário.
$query = "SELECT * FROM `busca_service`.`cliente` WHERE idcli=:idcli LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':idcli', $idcli);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();

# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$row = $stmt->fetch(PDO::FETCH_ASSOC);


# se o resultado retornado for igual a NULL, redireciona para a pagina de listar usuario.
# se não, cria a variavel row com dados do usuario selecionado.
if (!$row) {
    header('location: usuario_admin_listcli.php?error=Usuário inválido.');
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
                        <legend><b>Atualizar Cliente</b></legend>

                        <div class="dadosPessoais">
                            <div class="inputBox">
                                <input type="text" name="nome" id="nome" class="inputUser" required autofocus value="<?= isset($row) ? $row['nome'] : '' ?>">
                                <label for="nome" class="labelInput">Nome:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="email" name="email" id="email" class="inputUser" autofocus value="<?= isset($row) ? $row['email'] : '' ?>">
                                <label for="email" class="labelInput <?= isset($row) && !empty($row['email']) ? 'active' : '' ?>">E-mail:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="cpf" id="cpf" class="inputUser" readonly autofocus value="<?= isset($row) ? $row['cpf'] : '' ?>">
                                <label for="cpf" class="labelInput <?= isset($row) && !empty($row['cpf']) ? 'active' : '' ?>">CPF:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="tel" name="telefone-whatsapp" id="telefone-whatsapp" class="inputUser" minlength="14" maxlength="14" required autofocus value="<?= isset($row) ? $row['telefone'] : '' ?>">
                                <label for="telefone-whatsapp" class="labelInput">Celular (WhatsApp):<span class="asterisk">*</span></label>
                            </div>
                        </div>

                        <div class="endereco">
                            <div class="inputBox">
                                <input type="text" id="cep" name="cep" class="inputUser" maxlength="8" minlength="8" required autofocus value="<?= isset($row) ? $row['cep'] : '' ?>">
                                <label for="cep" class="labelInput">CEP:<span class="asterisk">*</span></label><br>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="estado" id="estado" class="inputUser" required autofocus value="<?= isset($row) ? $row['estado'] : '' ?>">
                                <label for="uf" class="labelInput">Estado:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="cidade" id="cidade" class="inputUser" required autofocus value="<?= isset($row) ? $row['cidade'] : '' ?>">
                                <label for="cidade" class="labelInput">Cidade:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="bairro" id="bairro" class="inputUser" required autofocus value="<?= isset($row) ? $row['bairro'] : '' ?>">
                                <label for="bairro" class="labelInput">Bairro:<span class="asterisk">*</span></label>
                            </div><br><br>
                        </div>

                        <div class="inputBox">
                            <label for="perfil" class="labelInput">Perfil<span class="asterisk">*</span></label>
                            <select name="perfil" id="perfil" class="inputUser" required><br><br>
                                <option value="CLI" <?= isset($row) && $row['perfil'] == 'CLI' ? 'selected' : '' ?>>
                                    Cliente</option>
                                <option value="ADM" <?= isset($row) && $row['perfil'] == 'ADM' ? 'selected' : '' ?>>
                                    Administrador</option>
                            </select>
                        </div><br>

                        <div class="inputBox">
                            <label for="status" class="labelInput">Status<span class="asterisk">*</span></label>
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
                        <a href="usuario_admin_listcli.php">
                            <button type="button" id="cancel" value="Cancelar" name="cancelar">Cancelar</button>
                        </a>
                    </div>
                </form>
            </section>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js">
            //biblioteca do JavaScript(necessário pra rodar códigos .js)
        </script>

        <script src="assets/js/cep.js">
            //formata cep e o faz preencher outros campos automaticamente
        </script>
        <script src="assets/js/telefone.js">
            //formata o telefone
        </script>
        <script src="assets/js/cpf.js">
            //formata o cpf
        </script>
        <script src="assets/js/email.js">
            //formata o email
        </script>

    </main>

</body>


</html>