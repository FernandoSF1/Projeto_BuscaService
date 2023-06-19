<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

// Incluindo os arquivos de cabeçalho
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
require_once "../database/conexao.php";

# Verifica se o usuário está logado
if (isset($_SESSION['usuario'])) {
    header("Location: index.php?error=Você já está logado em uma conta registrada!");
    exit;
}

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
    $status = isset($_POST['status']) ? $_POST['status'] : 1;

    # envio de imagens
    require_once 'cadastra_imagem.php';

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    // Insere os dados do profissional na tabela 'profissional' preciso colocar os valores dos ids?
    $query = "INSERT INTO `busca_service`.`profissional` (`nome`, `titulo`, `email`, `senha`, `cpf`, `telefone`, `telefone2`, `cep`, `estado`, `cidade`, `bairro`, `fotoprin`, `descricaonegocio`, `fotosec`, `fotosec2`, `status`)
                    VALUES (:nome, :titulo, :email, :senha, :cpf, :telefone, :telefone2, :cep, :estado, :cidade, :bairro, :fotoprin, :descricaonegocio, :fotosec, :fotosec2, :status)";
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
    $stmt->bindParam(':fotoprin', $destino_fotoprin);
    $stmt->bindParam(':descricaonegocio', $descricao);
    $stmt->bindParam(':fotosec', $destino_fotosec);
    $stmt->bindParam(':fotosec2', $destino_fotosec2);
    $stmt->bindParam(':status', $status);

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

        header('location: index.php?success=Profissional inserido com sucesso!');
    } else {
        header('location: cadastra_pro.php?error=Erro ao inserir o profissional!');
    }
}


# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# ordenando pelo campo perfil e nome.
$query = "SELECT * FROM `busca_service`.`servico` ORDER BY categoria, nome";
# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();
$stmt = $dbh->prepare($query);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();


# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                    <fieldset class="main_form">
                        <legend><b>Registrar-me como profissional</b></legend>
                        <br>

                        <div class="dadosPessoais">
                            <div class="inputBox">
                                <input type="text" name="nome" id="nome" class="inputUser" required>
                                <label for="nome" class="labelInput">Nome:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="titulo" id="titulo" class="inputUser" required>
                                <label for="titulo" class="labelInput">Título (seu nome ou do negócio):<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="email" name="email" id="email" class="inputUser" required>
                                <label for="email" class="labelInput">E-mail:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="password" name="senha" id="senha" class="inputUser" required>
                                <label for="senha" class="labelInput">Senha:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="cpf" id="cpf" class="inputUser" required>
                                <label for="cpf" class="labelInput">CPF:<span class="asterisk">*</span></label>
                            </div>
                            <br>

                            <div class="inputBox">
                                <input type="tel" name="telefone" id="telefone-whatsapp" class="inputUser" minlength="14" maxlength="14" required>
                                <label for="telefone-whatsapp" class="labelInput">Celular (WhatsApp):<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                            <input type="tel" name="telefone2" id="telefone-geral" class="inputUser" minlength="14" maxlength="14">
                                <label for="telefone-geral" class="labelInput">Outro telefone: (opcional)</label>
                            </div>
                        </div>

                        <div class="endereco">
                            <div class="inputBox">
                                <input type="text" id="cep" name="cep" class="inputUser" maxlength="8" minlength="8" required>
                                <label for="cep" class="labelInput">CEP:<span class="asterisk">*</span></label><br>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="estado" id="estado" class="inputUser" required>
                                <label for="uf" class="labelInput">Estado:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="cidade" id="cidade" class="inputUser" required>
                                <label for="cidade" class="labelInput">Cidade:<span class="asterisk">*</span></label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="bairro" id="bairro" class="inputUser" required>
                                <label for="bairro" class="labelInput">Bairro:<span class="asterisk">*</span></label>
                            </div>
                        </div>

                        <label for="servicos" class="servicos_oferecidos labelInput2">Serviços oferecidos:<span class="asterisk">*</span></label>
                        <div class="seleciona_servicos">

                            <div class="servicos_lista">

                                <?php
                                $servicesByCategory = [];


                                if (isset($servicesByCategory['null'])) {
                                    echo "<spam class='nenhum_servico'>Nenhum serviço cadastrado no sistema.<spam>";
                                } else {
                                    foreach ($rows as $row) {
                                        $categoria = $row['categoria'];
                                        $idserv = $row['idserv']; // Obtém o ID do serviço e armazena na variável $idserv
                                        $nome = $row['nome'];
                                        $servicesByCategory[$categoria][] = array('idserv' => $idserv, 'nome' => $nome); // Armazena o ID e o nome do serviço
                                    }

                                    // Exibe as categorias e serviços
                                    foreach ($servicesByCategory as $categoria => $servicos) {
                                        echo "<div class='categoria_servicos'>";
                                        echo "<p><b>$categoria:</b></p>"; // Exibe o nome da categoria

                                        echo "<ul>"; // Abre uma lista não ordenada para os serviços

                                        foreach ($servicos as $servico) {
                                            $idserv = $servico['idserv'];
                                            $nome = $servico['nome'];
                                            echo "<li><input type='checkbox' name='servico[]' value='$idserv' id='servico_$idserv'> <label for='servico_$idserv'>$nome</label></li>"; // Exibe o serviço com o ID como valor
                                        }

                                        echo "</ul>"; // Fecha a lista não ordenada
                                        echo "</div>";
                                    }
                                }
                                ?>

                            </div>
                        </div>
                        <span class="servicos-obrigatorio" style="display: none;">Selecione pelo menos um serviço.</span>

                        <div class="inputBox">
                            <label for="fotoprin" class="labelInput2">Imagem do perfil:<span class="asterisk">*</span></label>
                            <p><br>
                                <input type="file" class="fileInput" name="fotoprin" id="fotoprin" data-titulo="Imagem" data-obrigatorio="1" accept="image/*" required>
                                <label for="fotoprin" class="fileInputLabel">Escolher arquivo</label>
                                <span id="arquivo_selecionado_perfil"></span>
                            </p>
                            <span class="campo-obrigatorio" style="display: none;">Por favor, selecione uma imagem para o perfil.</span>
                        </div>

                        <div class="inputBox">
                            <label for="field_conteudo" class="labelInput2">Fale um pouco sobre você ou sobre o seu negócio:<span class="asterisk">*</span></label>
                            <textarea class="descricao_form" id="field_conteudo" name="descricao" rows="6" required></textarea>
                        </div>

                        <div class="inputBox">
                            <label for="fotosec" class="labelInput2">Envie fotos do seu trabalho aqui: (opcional)</label>
                            <p><br>
                                <input type="file" class="fileInput" name="fotosec" id="fotosec" data-titulo="Imagem" accept="image/*">
                                <label for="fotosec" class="fileInputLabel">Escolher arquivo</label>
                                <span id="arquivo_selecionado_trabalho1"></span>
                            </p>
                        </div>

                        <div class="inputBox">
                            <label for="fotosec2" class="labelInput2">Envie mais uma foto do seu trabalho: (opcional)</label>
                            <p><br>
                                <input type="file" class="fileInput" name="fotosec2" id="fotosec2" data-titulo="Imagem" accept="image/*">
                                <label for="fotosec2" class="fileInputLabel">Escolher arquivo</label>
                                <span id="arquivo_selecionado_trabalho2"></span>
                            </p>
                        </div>
                        <br><br>

                    </fieldset>
                    <div class="btn_alinhamento">
                        <button type="submit" id="submit" value="Enviar" name="salvar">Enviar</button>
                        </a>
                        <a href="index.php">
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