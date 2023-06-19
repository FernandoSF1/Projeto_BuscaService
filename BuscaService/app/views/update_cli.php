<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header e a classe de conexão com o banco de dados.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como cliente
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'CLI') {
    header("Location: index.php?error=Você precisa estar logado como cliente para ter acesso a este recurso");
    exit;
}

// Verifica se o ID do cliente está definido na sessão
if (isset($_SESSION['usuario']['idcli'])) {
    $idcli = $_SESSION['usuario']['idcli'];
} else {
    // Verifica se o parâmetro GET 'idcli' está presente
    if (isset($_GET['idcli'])) {
        // Decodifica o valor do parâmetro GET usando base64_decode
        $idcli = base64_decode($_GET['idcli']);
    } else {
        $idcli = 0; // Define um valor padrão caso nenhum ID seja encontrado
    }
}


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


    # cria uma consulta banco de dados atualizando um usuario existente. 
    # usando como parametros os campos nome e password.
    $query = "UPDATE `busca_service`.`cliente` SET `nome` = :nome, `email` = :email, `telefone` = :telefone, `cep` = :cep, `estado` = :estado, `cidade` = :cidade, `bairro` = :bairro 
                    WHERE idcli = :idcli";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':idcli', $idcli);

    # executa a consulta banco de dados para inserir o resultado.
    $stmt->execute();

    # verifica se a quantidade de registros inseridos é maior que zero.
    # se sim, redireciona para a pagina de perfil com mensagem de sucesso.
    # se não, redireciona para a pagina de cadastro com mensagem de erro.
    if ($stmt->rowCount()) {
        header('location: perfil_cli.php?success=Dados atualizados com sucesso!');
        $_SESSION['usuario']['nome'] = $nome;
    } else {
        // $error = $dbh->errorInfo();
        // var_dump($error);
        header('location: perfil_cli.php?error=Erro ao atualizar os seus dados!');
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
    header('location: perfil_cli.php?error=Usuário inválido.');
}

# destroi a conexao com o banco de dados.
$dbh = null;
?>

<body>
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
                                <label for="nome" class="labelInput">Nome</label>
                            </div>

                            <div class="inputBox">
                                <input type="email" name="email" id="email" class="inputUser" autofocus value="<?= isset($row) ? $row['email'] : '' ?>">
                                <label for="email" class="labelInput <?= isset($row) && !empty($row['email']) ? 'active' : '' ?>">E-mail:</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="cpf" id="cpf" class="inputUser" readonly autofocus value="<?= isset($row) ? $row['cpf'] : '' ?>">
                                <label for="cpf" class="labelInput <?= isset($row) && !empty($row['cpf']) ? 'active' : '' ?>">CPF:</label>
                            </div>

                            <div class="inputBox">
                                <input type="tel" name="telefone-whatsapp" id="telefone-whatsapp" class="inputUser" minlength="14" maxlength="14" required autofocus value="<?= isset($row) ? $row['telefone'] : '' ?>">
                                <label for="telefone-whatsapp" class="labelInput">Celular (WhatsApp):</label>
                            </div>
                        </div>

                        <div class="endereco">
                            <div class="inputBox">
                                <input type="text" id="cep" name="cep" class="inputUser" maxlength="8" minlength="8" required autofocus value="<?= isset($row) ? $row['cep'] : '' ?>">
                                <label for="cep" class="labelInput">CEP:</label><br>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="estado" id="estado" class="inputUser" required autofocus value="<?= isset($row) ? $row['estado'] : '' ?>">
                                <label for="uf" class="labelInput">Estado:</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="cidade" id="cidade" class="inputUser" required autofocus value="<?= isset($row) ? $row['cidade'] : '' ?>">
                                <label for="cidade" class="labelInput">Cidade:</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="bairro" id="bairro" class="inputUser" required autofocus value="<?= isset($row) ? $row['bairro'] : '' ?>">
                                <label for="bairro" class="labelInput">Bairro:</label>
                            </div><br><br>
                        </div>


                    </fieldset>
                    <div class="btn_alinhamento">
                        <a href="perfil_cli.php">
                            <button type="submit" id="submit" value="Enviar" name="salvar" onclick="return confirm('Deseja realmente alterar algum dado?');">Enviar</button>
                        </a>
                        <a href="perfil_cli.php">
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

    </main>

</body>


</html>