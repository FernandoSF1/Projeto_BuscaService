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
    header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
    exit;
}

# verifica se uma variavel id foi passada via GET 
$idpro = isset($_GET['idpro']) ? $_GET['idpro'] : 0;

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['senha']) ? md5($_POST['senha']) : 'USU';
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $telefone2 = $_POST['telefone2'] ?? '';
    $cep = isset($_POST['cep']) ? $_POST['cep'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';
    $bairro = isset($_POST['bairro']) ? $_POST['bairro'] : '';
    $fotoprin = isset($_FILES['fotoprin']) ? $_FILES['fotoprin'] : null;
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $fotosec = $_FILES['fotosec'] ?? null;
    $fotosec2 = $_FILES['fotosec2'] ?? null;
    $status = isset($_POST['status']) ? $_POST['status'] : 0;


    // Insere os dados do profissional na tabela 'profissional' preciso colocar os valores dos ids?
    $query = "UPDATE `busca_service`.`profissional` SET `nome` = :nome, `titulo` = :titulo, `email` = :email, `senha` = :senha, `cpf` = :cpf, `telefone` = :telefone, `telefone2` = :telefone2, `cep` = :cep, `estado` = :estado, `cidade` = :cidade, `bairro` = :bairro, `fotoprin` = :fotoprin, `descricao` = :descricao, `fotosec` = :fotosec, `fotosec2` = :fotosec2, `status` = :status 
                    WHERE idpro = :idpro";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $password);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':telefone2', $telefone2);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':fotoprin', $fotoprin);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':fotosec', $fotosec);
    $stmt->bindParam(':fotosec2', $fotosec2);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':idpro', $idpro);


    $stmt->execute();

    // Obtém o ID do profissional inserido
    $idpro = $dbh->lastInsertId();

    // Verifica se a quantidade de registros inseridos é maior que zero
    if ($stmt->rowCount()) {
        // Verifica se foram selecionados serviços no formulário
        if (isset($_POST['servico']) && is_array($_POST['servico'])) {
            $servicosSelecionados = $_POST['servico'];

            // Insere as relações entre o profissional e os serviços na tabela 'profissional_has_servico'
            $query = "INSERT INTO `busca_service`.`profissional_has_servico` (`idpro`, `idserv`) VALUES (:idpro, :idserv)";
            $stmt = $dbh->prepare($query);

            foreach ($servicosSelecionados as $idserv) {
                $stmt->bindValue(':idpro', $idpro); // Atribui o ID do profissional
                $stmt->bindValue(':idserv', $idserv);
                $stmt->execute();
            }
        }

        header('location: usuario_admin_listpro.php?success=Profissional atualizado com sucesso!');
    } else {
        header('location: usuario_admin_updpro.php?error=Erro ao atualizar o profissional!');
    }
}


# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# filtrando pelo id do usuário.
$query = "SELECT * FROM `busca_service`.`profissional` WHERE idpro=:idpro LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':idpro', $idpro);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();


# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

# destroi a conexao com o banco de dados.
$dbh = null;

?>

<body>
    <?php require_once 'layouts/admin/menu.php'; ?>
    <main class="bg_form">
        <div class="main_opc">
            <?php
            # verifca se existe uma mensagem de erro enviada via GET.
            # se sim, exibe a mensagem enviada no cabeçalho.
            if (isset($_GET['error'])) { ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Profissional',
                        text: '<?= $_GET['error'] ?>',
                    })
                </script>
            <?php } ?>
            <section>
                <form action="" method="post" class="box">
                    <fieldset>
                        <legend><b>Atualizar Profissional</b></legend>

                        <div class="dadosPessoais">
                            <div class="inputBox">
                                <input type="text" name="nome" id="nome" class="inputUser" required autofocus value="<?= isset($row) ? $row['nome'] : '' ?>">
                                <label for="nome" class="labelInput">Nome</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="titulo" id="titulo" class="inputUser" required autofocus value="<?= isset($row) ? $row['titulo'] : '' ?>">>
                                <label for="titulo" class="labelInput">Título (seu nome ou do negócio):</label>
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
                                <input type="tel" name="telefone" id="telefone-whatsapp" class="inputUser" minlength="14" maxlength="14" required>
                                <label for="telefone-whatsapp" class="labelInput">Celular (WhatsApp):</label>
                            </div>

                            <div class="inputBox">
                                <input type="tel" name="telefone2" id="telefone-geral" class="inputUser" minlength="14" maxlength="14" required>
                                <label for="telefone-geral" class="labelInput">Telefone:</label>
                            </div>
                        </div>

                        <div class="endereco">
                            <div class="inputBox">
                                <input type="text" id="cep" name="cep" class="inputUser" maxlength="8" minlength="8" required>
                                <label for="cep" class="labelInput">CEP:</label><br>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="estado" id="estado" class="inputUser" required>
                                <label for="uf" class="labelInput">Estado:</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="cidade" id="cidade" class="inputUser" required>
                                <label for="cidade" class="labelInput">Cidade:</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="bairro" id="bairro" class="inputUser" required>
                                <label for="bairro" class="labelInput">Bairro:</label>
                            </div>
                        </div>

                        <div class="inputBox">
                            <label for="perfil" id="perfilLabel">Perfil</label><br>
                            <select name="perfil" id="perfil"><br><br>
                                <option value="CLI" <?= isset($row) && $row['perfil'] == 'CLI' ? 'selected' : '' ?>>
                                    Cliente</option>
                                <option value="PRO" <?= isset($row) && $row['perfil'] == 'PRO' ? 'selected' : '' ?>>
                                    Profissional</option>
                                <option value="ADM" <?= isset($row) && $row['perfil'] == 'ADM' ? 'selected' : '' ?>>
                                    Administrador</option>
                            </select>
                        </div><br>

                        <div class="inputBox">
                            <label for="status" class="statusLabel">Status</label><br>
                            <div class="select-wrapper">
                                <select name="status" id="status"><br><br>
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

        <script src="assets/js/checkbox_limit.js">
            //faz com que só sejam marcadas, no máximo, 6 checkboxs
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