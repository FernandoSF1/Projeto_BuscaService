<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header e a classe de conexão com o banco de dados.
require_once 'layouts/site/header.php';
require_once "../database/conexao.php";

# verifica se existe sessão de usuario e se ele é administrador.
# se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
# sai da pagina.

# verifica se uma variavel id foi passada via GET 
$idpro = isset($_GET['idpro']) ? $_GET['idpro'] : 0;

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
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $telefone2 = $_POST['telefone2'] ?? '';
    $cep = isset($_POST['cep']) ? $_POST['cep'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';
    $bairro = isset($_POST['bairro']) ? $_POST['bairro'] : '';
    $fotoprin = isset($_FILES['fotoprin']) ? $_FILES['fotoprin'] : null;
    $descricaonegocio = isset($_POST['descricaonegocio']) ? $_POST['descricaonegocio'] : '';
    $fotosec = $_FILES['fotosec'] ?? null;
    $fotosec2 = $_FILES['fotosec2'] ?? null;
    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : 0;
    $listaServicos = $_POST['servico'];
    

    // Insere os dados do profissional na tabela 'profissional'
    $query = "UPDATE `busca_service`.`profissional` SET `nome` = :nome, `titulo` = :titulo, `email` = :email, `cpf` = :cpf, `telefone` = :telefone, `telefone2` = :telefone2, `cep` = :cep, `estado` = :estado, `cidade` = :cidade, `bairro` = :bairro, `fotoprin` = :fotoprin, `descricaonegocio` = :descricaonegocio, `fotosec` = :fotosec, `fotosec2` = :fotosec2, `perfil` = :perfil, `status` = :status 
                    WHERE idpro = :idpro";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':telefone2', $telefone2);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':cidade', $cidade);
    $stmt->bindParam(':bairro', $bairro);
    $stmt->bindParam(':fotoprin', $fotoprin);
    $stmt->bindParam(':descricaonegocio', $descricaonegocio);
    $stmt->bindParam(':fotosec', $fotosec);
    $stmt->bindParam(':fotosec2', $fotosec2);
    $stmt->bindParam(':perfil', $perfil);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':idpro', $idpro);

    $stmt->execute();
    
    $query = "DELETE FROM `busca_service`.`profissional_has_servico` WHERE idpro=:idpro";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':idpro', $idpro);
    $stmt->execute();

    foreach($listaServicos as $servicoInserir) {
        $query = "INSERT INTO `busca_service`.`profissional_has_servico` (idpro, idserv) VALUES (:idpro, :idserv)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idpro', $idpro);
        $stmt->bindParam(':idserv', $servicoInserir);
        $stmt->execute();
    }
    
    if ($stmt->rowCount()) {
        header('location: usuario_admin_listprocopy.php?success=Profissional atualizado com sucesso!');
    } else {
        header('location: usuario_admin_updprocopy.php?error=Erro ao atualizar o profissional!');
    }
    
    
    //     // Verifica se foram selecionados serviços no formulário
    //     if (isset($_POST['servico']) && is_array($_POST['servico'])) {
    //         $servicosSelecionados = $_POST['servico'];

    //         # Remove os serviços não selecionados
    //     $servicosRemovidos = array_diff($servicosMarcados, $servicosSelecionados);
    //     // echo '<pre>'; var_dump($servicosRemovidos); exit;
        
    //     if (!empty($servicosRemovidos)) {
    //         # Remove as associações do profissional com os serviços não selecionados
    //         $placeholders = rtrim(str_repeat('?, ', count($servicosRemovidos)), ', ');
    //         $query = "DELETE FROM `busca_service`.`profissional_has_servico` WHERE idpro=:idpro AND idserv IN ($placeholders)";
    //         $stmt = $dbh->prepare($query);
    //         $stmt->bindParam(':idpro', $idpro);
    //         $stmt->execute($servicosRemovidos);
    //     }
    
    // # Atualiza os serviços marcados com os selecionados
    // $servicosAtualizados = array_unique(array_merge($servicosMarcados, $servicosSelecionados));
    // $servicosAtualizados = array_filter($servicosAtualizados);
    
    // # Remove as associações duplicadas
    // $query = "DELETE FROM `busca_service`.`profissional_has_servico` WHERE idpro=:idpro";
    // $stmt = $dbh->prepare($query);
    // $stmt->bindParam(':idpro', $idpro);
    // $stmt->execute();
    
    // # Insere as novas associações do profissional com os serviços
    // $placeholders = rtrim(str_repeat('?, ', count($servicosAtualizados)), ', ');

    // $query = "INSERT INTO `busca_service`.`profissional_has_servico` (idpro, idserv) VALUES (:idpro, $placeholders)";
    // $stmt = $dbh->prepare($query);
    // $stmt->bindParam(':idpro', $idpro);
    // $stmt->execute($servicosAtualizados);

    //         # Consulta todos os serviços disponíveis
    //         $query = "SELECT * FROM `busca_service`.`servico`";
    //         $stmt = $dbh->prepare($query);
    //         $stmt->execute();

    //         # Obtém todos os serviços em um array
    //         $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     }

    //     header('location: usuario_admin_listprocopy.php?success=Profissional atualizado com sucesso!');
    // } else {
    //     header('location: usuario_admin_updprocopy.php?error=Erro ao atualizar o profissional!');
    // }
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

                        <label for="servicos" class="servicos_oferecidos">Serviços oferecidos:</label>
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
            <label for="fotoprin" class="labelInput">Imagem do perfil:</label>
            <p><br><br>
                <input type="file" class="fileInput" name="fotoprin" id="fotoprin" data-titulo="Imagem" data-obrigatorio="1" accept="image/*" required>
                <label for="fotoprin" class="fileInputLabel">Escolher arquivo</label>
                <span id="arquivo_selecionado_perfil"></span>
            </p>
            <span class="campo-obrigatorio" style="display: none;">Por favor, selecione uma imagem para o perfil.</span>
        </div>

        <div class="inputBox">
            <label for="field_conteudo" class="labelInput">Fale um pouco sobre você ou sobre o seu negócio:</label><br>
            <textarea class="descricao" id="field_conteudo" name="descricaonegocio" rows="6" required autofocus><?= isset($row) ? $row['descricaonegocio'] : '' ?></textarea>
        </div>

        <div class="inputBox">
            <label for="fotosec" class="labelInput">Envie fotos do seu trabalho aqui
                (opcional):</label>
            <p><br><br>
                <input type="file" class="fileInput" name="fotosec" id="fotosec" data-titulo="Imagem" accept="image/*">
                <label for="fotosec" class="fileInputLabel">Escolher arquivo</label>
                <span id="arquivo_selecionado_trabalho1"></span>
            </p>
        </div>

        <div class="inputBox">
            <label for="fotosec2" class="labelInput">Envie mais uma foto do seu trabalho
                (opcional):</label>
            <p><br><br>
                <input type="file" class="fileInput" name="fotosec2" id="fotosec2" data-titulo="Imagem" accept="image/*">
                <label for="fotosec2" class="fileInputLabel">Escolher arquivo</label>
                <span id="arquivo_selecionado_trabalho2"></span>
            </p>
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
                        </div>

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
                        <a href="usuario_admin_listprocopy.php?idpro=<?php echo $idpro; ?>">
                            <button type="submit" id="submit" value="Enviar" name="salvar">Enviar</button>
                        </a>
                        <a href="usuario_admin_listprocopy.php">
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