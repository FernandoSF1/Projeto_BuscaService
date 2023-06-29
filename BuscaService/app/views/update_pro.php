<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header e a classe de conexão com o banco de dados.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como profissional
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'PRO') {
    header("Location: index.php?error=Você precisa estar logado como profissional para ter acesso a este recurso");
    exit;
}

// Verifica se o ID do profissional está definido na sessão
if (isset($_SESSION['usuario']['idpro'])) {
    $idpro = $_SESSION['usuario']['idpro'];
} else {
    // Verifica se o parâmetro GET 'idpro' está presente
    if (isset($_GET['idpro'])) {
        // Decodifica o valor do parâmetro GET usando base64_decode
        $idpro = base64_decode($_GET['idpro']);
    } else {
        $idpro = 0; // Define um valor padrão caso nenhum ID seja encontrado
    }
}

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# Consulta os serviços marcados pelo profissional
$query = "SELECT idserv FROM `busca_service`.`profissional_has_servico` WHERE idpro=:idpro";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':idpro', $idpro);
$stmt->execute();

# Obtém os IDs dos serviços marcados em um array
$servicosMarcados = $stmt->fetchAll(PDO::FETCH_COLUMN);

// $teste = array_search('16', $servicosMarcados);
// echo '<pre>'; var_dump($servicosMarcados, $teste); exit;

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $telefone2 = $_POST['telefone2'] ?? '';
    $cep = isset($_POST['cep']) ? $_POST['cep'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';
    $bairro = isset($_POST['bairro']) ? $_POST['bairro'] : '';
    $descricaonegocio = isset($_POST['descricaonegocio']) ? $_POST['descricaonegocio'] : '';
    $listaServicos = $_POST['servico'];


    # cria uma consulta no banco de dados buscando todos os dados da tabela profissional
    # filtrando pelo id do profissional.
    $query = "SELECT * FROM `busca_service`.`profissional` WHERE idpro=:idpro LIMIT 1";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':idpro', $idpro);

    # executa a consulta no banco de dados e aguarda o resultado.
    $stmt->execute();

    # Faz um fetch para trazer os dados existentes, se existirem, em um array na variável $row.
    # se não existir, retorna null
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    # Verificar se a imagem atual do $row está vazia e se o campo 'fotoprin' está vazio
    if (empty($row['fotoprin']) && empty($_FILES['fotoprin']['name'])) {
        # Se ambos estiverem vazios, exiba uma mensagem de erro e interrompa o processamento do formulário
        echo "Por favor, selecione uma imagem para o perfil.";
        return;
    }

    // CADASTRO DE IMAGENS

    # Verificar o campo 'fotoprin'
    if (!empty($_FILES['fotoprin']['name'])) {
        require_once 'update_fotoprin.php';
        # Atualize o valor de $destino_fotoprin aqui, com base na lógica do script 'update_fotoprin.php'
    } else {
        $destino_fotoprin = $row['fotoprin'];
    }

    # Repita a mesma lógica para os campos 'fotosec' e 'fotosec2'
    if (!empty($_FILES['fotosec']['name'])) {
        require_once 'update_fotosec.php';
        # Atualize o valor de $destino_fotosec aqui
    } else {
        $destino_fotosec = $row['fotosec'];
    }

    if (!empty($_FILES['fotosec2']['name'])) {
        require_once 'update_fotosec2.php';
        # Atualize o valor de $destino_fotosec2 aqui
    } else {
        $destino_fotosec2 = $row['fotosec2'];
    }

    // ...

    // Insere os dados do profissional na tabela 'profissional'
    $query = "UPDATE `busca_service`.`profissional` SET `nome` = :nome, `titulo` = :titulo, `email` = :email, `telefone` = :telefone, `telefone2` = :telefone2, `cep` = :cep, `estado` = :estado, `cidade` = :cidade, `bairro` = :bairro, `fotoprin` = :fotoprin, `descricaonegocio` = :descricaonegocio, `fotosec` = :fotosec, `fotosec2` = :fotosec2 
                    WHERE idpro = :idpro";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':telefone2', $telefone2);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':fotoprin', $destino_fotoprin);
    $stmt->bindParam(':descricaonegocio', $descricaonegocio);
    $stmt->bindParam(':fotosec', $destino_fotosec);
    $stmt->bindParam(':fotosec2', $destino_fotosec2);
    $stmt->bindParam(':idpro', $idpro);

    $stmt->execute();

    $query = "DELETE FROM `busca_service`.`profissional_has_servico` WHERE idpro=:idpro";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':idpro', $idpro);
    $stmt->execute();

    foreach ($listaServicos as $servicoInserir) {
        $query = "INSERT INTO `busca_service`.`profissional_has_servico` (idpro, idserv) VALUES (:idpro, :idserv)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idpro', $idpro);
        $stmt->bindParam(':idserv', $servicoInserir);
        $stmt->execute();
    }

    if ($stmt->rowCount()) {
        header('location: perfil_pro.php?success=Profissional atualizado com sucesso!');
        $_SESSION['usuario']['nome'] = $nome;
    } else {
        header('location: update_pro.php?error=Erro ao atualizar o profissional!');
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
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//echo "<pre>";var_dump($row);exit;

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
                <form action="" method="post" class="box" enctype="multipart/form-data" id="formulario_img">
                    <fieldset>
                        <legend><b>Atualizar Profissional</b></legend>

                        <div class="dadosPessoais">
                            <div class="inputBox">
                                <input type="text" name="nome" id="nome" class="inputUser" required autofocus value="<?= isset($row) ? $row['nome'] : '' ?>">
                                <label for="nome" class="labelInput">Nome</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="titulo" id="titulo" class="inputUser" required autofocus value="<?= isset($row) ? $row['titulo'] : '' ?>">
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
                                <input type="tel" name="telefone" id="telefone-whatsapp" class="inputUser" minlength="14" maxlength="14" required autofocus value="<?= isset($row) ? $row['telefone'] : '' ?>">
                                <label for="telefone-whatsapp" class="labelInput">Celular (WhatsApp):</label>
                            </div>

                            <div class="inputBox">
                                <input type="tel" name="telefone2" id="telefone-geral" class="inputUser" minlength="14" maxlength="14" required autofocus value="<?= isset($row) ? $row['telefone2'] : '' ?>">
                                <label for="telefone-geral" class="labelInput">Telefone:</label>
                            </div>
                        </div>

                        <div class="endereco">
                            <div class="inputBox">
                                <input type="text" id="cep" name="cep" class="inputUser" maxlength="8" minlength="8" autofocus value="<?= isset($row) ? $row['cep'] : '' ?>">
                                <label for="cep" class="labelInput">CEP:</label><br>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="estado" id="estado" class="inputUser" autofocus value="<?= isset($row) ? $row['estado'] : '' ?>">
                                <label for="uf" class="labelInput">Estado:</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="cidade" id="cidade" class="inputUser" autofocus value="<?= isset($row) ? $row['cidade'] : '' ?>">
                                <label for="cidade" class="labelInput">Cidade:</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="bairro" id="bairro" class="inputUser" autofocus value="<?= isset($row) ? $row['bairro'] : '' ?>">
                                <label for="bairro" class="labelInput">Bairro:</label>
                            </div>
                        </div>

                        <label for="servicos" class="servicos_oferecidos labelInput2">Serviços oferecidos:</label>
                        <div class="seleciona_servicos">
                            <div class="servicos_lista">
                                <?php
                                $dbh = Conexao::getInstance();
                                $query = "SELECT * FROM `busca_service`.`servico`";
                                $stmt = $dbh->prepare($query);
                                $stmt->execute();
                                $rowsServicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                // echo '<pre>'; var_dump($rowsServicos); exit;

                                $servicesByCategory = [];

                                foreach ($rowsServicos as $rowServico) {
                                    $categoria = $rowServico['categoria'];
                                    $idserv = $rowServico['idserv'];
                                    $nome = $rowServico['nome'];
                                    $servicesByCategory[$categoria][] = array('idserv' => $idserv, 'nome' => $nome);
                                }

                                foreach ($servicesByCategory as $categoria => $servicos) {
                                    echo "<div class='categoria_servicos'>";
                                    echo "<p><b>$categoria:</b></p>";
                                    echo "<ul>";

                                    foreach ($servicos as $servico) {
                                        $idserv = $servico['idserv'];
                                        $nome = $servico['nome'];

                                        $checked = in_array($idserv, $servicosMarcados) ? 'checked' : '';
                                        echo "<li><input type='checkbox' name='servico[]' value='$idserv' id='servico_$idserv' $checked> <label for='servico_$idserv'>$nome</label></li>";
                                    }

                                    echo "</ul>";
                                    echo "</div>";
                                }
                                ?>
                            </div>
                        </div>

                        <span class="servicos-obrigatorio" style="display: none;">Selecione pelo menos um serviço.</span>

                        <div class="inputBox">
                            <label for="fotoprin" class="labelInput2">Imagem do perfil:</label>
                            <p><br>
                                <?php
                                if (!empty($row['fotoprin'])) {
                                    echo '<img src="' . $row['fotoprin'] . '" alt="Imagem do perfil" style="width: 50px; height: 50px;">';
                                }
                                ?>
                                <input type="file" class="fileInput" name="fotoprin" id="fotoprin" data-titulo="Imagem" data-obrigatorio="1" accept="image/*">
                                <label for="fotoprin" class="fileInputLabel">Escolher arquivo</label>
                                <span id="arquivo_selecionado_perfil"></span>
                            </p>
                            <span class="campo-obrigatorio" style="display: none;">Por favor, selecione uma imagem para o perfil.</span>
                        </div>


                        <div class="inputBox">
                            <label for="field_conteudo" class="labelInput2">Fale um pouco sobre você ou sobre o seu negócio:</label>
                            <textarea class="descricao_form" id="field_conteudo" name="descricaonegocio" rows="6" required autofocus><?= isset($row) ? $row['descricaonegocio'] : '' ?></textarea>
                        </div>

                        <div class="inputBox">
                            <label for="fotosec" class="labelInput2">Envie fotos do seu trabalho aqui (opcional):</label>
                            <p><br>
                                <?php
                                if (!empty($row['fotosec'])) {
                                    echo '<img src="' . $row['fotosec'] . '" alt="Primeira imagem do trabalho" style="width: 50px; height: 50px;">';
                                }
                                ?>
                                <input type="file" class="fileInput" name="fotosec" id="fotosec" data-titulo="Imagem" accept="image/*">
                                <label for="fotosec" class="fileInputLabel">Escolher arquivo</label>
                                <span id="arquivo_selecionado_trabalho1"></span>
                            </p>
                        </div>


                        <div class="inputBox">
                            <label for="fotosec2" class="labelInput2">Envie mais uma foto do seu trabalho (opcional):</label>
                            <p><br>
                                <?php
                                if (!empty($row['fotosec2'])) {
                                    echo '<img src="' . $row['fotosec2'] . '" alt="Segunda imagem do trabalho" style="width: 50px; height: 50px;">';
                                }
                                ?>
                                <input type="file" class="fileInput" name="fotosec2" id="fotosec2" data-titulo="Imagem" accept="image/*">
                                <label for="fotosec2" class="fileInputLabel">Escolher arquivo</label>
                                <span id="arquivo_selecionado_trabalho2"></span>
                            </p>
                        </div><br><br>

                    </fieldset>
                    <div class="btn_alinhamento">
                        <a href="perfil_pro.php?idpro=<?php echo $idpro; ?>">
                            <button type="submit" id="submit" value="Enviar" name="salvar">Enviar</button>
                        </a>
                        <a href="perfil_pro.php">
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
        <script src="assets/js/exibir_file.js">
            //exibir nome do arquivo selecionado
        </script>
        <script src="assets/js/min_checkbox.js">
            //formata o cpf
        </script>
        <script src="assets/js/email.js">
            //formata o email
        </script>

    </main>

</body>


</html>