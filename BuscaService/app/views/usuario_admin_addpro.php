<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once 'layouts/site/header.php';
    require_once "../database/conexao.php";

    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
        header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }
    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['senha']) ? md5($_POST['senha']) : 'USU';
        $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
        $telefone = isset($_POST['telefone-whatsapp']) ? $_POST['telefone-whatsapp'] : '';
        $telefone2 = isset($_POST['telefone-geral']) ? $_POST['telefone-geral'] : '';
        $cep = isset($_POST['cep']) ? $_POST['cep'] : '';
        $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
        $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';
        $bairro = isset($_POST['bairro']) ? $_POST['bairro'] : '';
        $logradouro = isset($_POST['endereco']) ? $_POST['endereco'] : '';
        $servico = isset($_POST['servico']) ? $_POST['servico'] : array();
        $fotoprin = isset($_FILES['fotoprin']) ? $_FILES['fotoprin'] : null;
        $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
        $fotosec = isset($_FILES['fotosec']) ? $_FILES['fotosec'] : null;
        $fotosec2 = isset($_FILES['fotosec2']) ? $_FILES['fotosec2'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        

        # cria uma consulta banco de dados verificando se o usuario existe 
        # usando como parametros os campos nome e password.
        $query = "INSERT INTO `busca_service`.`profissional` (`nome`, `titulo`, `email`, `senha`, `cpf`, `telefone`, `telefone2`, `cep`, `estado`, `cidade`, `bairro`, `logradouro`, `fotoprin`, `descricao`, `fotosec`, `fotosec2`, `status`)
                    VALUES (:nome, :titulo, :email, :senha, :cpf, :telefone, :telefone2, :cep, :estado, :cidade, :bairro, :logradouro, :fotoprin, :descricao, :fotosec, :fotosec2, :status)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $password);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':telefone-whatsapp', $telefone);
        $stmt->bindParam(':telefone-geral', $telefone2);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':endereco', $logradouro);
        $stmt->bindParam(':fotoprin', $fotoprin);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':fotosec', $fotosec);
        $stmt->bindParam(':fotosec2', $fotosec2);
        $stmt->bindParam(':status', $status);

        # executa a consulta banco de dados para inserir o resultado.
        $stmt->execute();

        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: usuario_admin.php?success=Profissional inserido com sucesso!');
        } else {
            header('location: usuario_admin_addpro.php?error=Erro ao inserir o profissional!');
        }

    }

    # cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
    # ordenando pelo campo perfil e nome.
    $query = "SELECT * FROM `busca_service`.`servico` ORDER BY categoria, nome";
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
    <?php require_once 'layouts/admin/menu.php';?>
    <main class="bg_form">
        <div class="main_opc">
            <?php
                # verifca se existe uma mensagem de erro enviada via GET.
                # se sim, exibe a mensagem enviada no cabeçalho.
                if(isset($_GET['error'])) { ?>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Profissional',
                text: '<?=$_GET['error'] ?>',
            })
            </script>
            <?php } ?>
            <section>
                <form action="" method="post" class="box" enctype="multipart/form-data">
                    <fieldset class="main_form">
                        <legend><b>Cadastrar Profissionais</b></legend>
                        <br>

                        <div class="dadosPessoais">
                            <div class="inputBox">
                                <input type="text" name="nome" id="nome" class="inputUser" required>
                                <label for="nome" class="labelInput">Nome completo:</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="titulo" id="titulo" class="inputUser" required>
                                <label for="titulo" class="labelInput">Título (seu nome ou do negócio):</label>
                            </div>

                            <div class="inputBox">
                                <input type="email" name="email" id="email" class="inputUser" required>
                                <label for="email" class="labelInput">E-mail:</label>
                            </div>

                            <div class="inputBox">
                                <input type="password" name="senha" id="senha" class="inputUser" required>
                                <label for="senha" class="labelInput">Senha:</label>
                            </div>

                            <div class="inputBox">
                                <input type="text" name="cpf" id="cpf" class="inputUser" required>
                                <label for="cpf" class="labelInput">CPF:</label>
                            </div>
                            <br>

                            <div class="inputBox">
                                <input type="tel" name="telefone-whatsapp" id="telefone-whatsapp" class="inputUser"
                                    minlength="14" maxlength="14" required>
                                <label for="telefone-whatsapp" class="labelInput">Celular (WhatsApp):</label>
                            </div>

                            <div class="inputBox">
                                <input type="tel" name="telefone-geral" id="telefone-geral" class="inputUser"
                                    minlength="14" maxlength="14" required>
                                <label for="telefone-geral" class="labelInput">Telefone:</label>
                            </div>

                        </div>

                        <div class="endereco">
                            <div class="inputBox">
                                <input type="text" id="cep" name="cep" class="inputUser" maxlength="8" minlength="8"
                                    required>
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

                            <div class="inputBox">
                                <input type="text" name="endereco" id="endereco" class="inputUser" required>
                                <label for="endereco" class="labelInput">Logradouro:</label>
                            </div>
                        </div>


                        <label for="servicos" class="servicos_oferecidos">Serviços oferecidos:</label>
                        <div class="seleciona_servicos">

                            <div class="servicos_lista">

                                <?php
        $currentCategory = null;
        $servicesByCategory = []; // Array para armazenar os serviços agrupados por categoria
        
        // Agrupa os serviços por categoria
        foreach ($rows as $row) {
            $categoria = $row['categoria'];
            $nome = $row['nome'];
            $servicesByCategory[$categoria][] = $nome;
        }
        
        // Exibe as categorias e serviços
        foreach ($servicesByCategory as $categoria => $servicos) {
            echo "<div class='categoria_servicos'>";
            echo "<p><b>$categoria:</b></p>"; // Exibe o nome da categoria
            
            echo "<ul>"; // Abre uma lista não ordenada para os serviços
            
            foreach ($servicos as $servico) {
                echo "<li><input type='checkbox' name='servico[]' value='$servico' id='servico_$servico'> <label for='servico_$servico'>$servico</label></li>"; // Exibe o serviço
            }
            
            echo "</ul>"; // Fecha a lista não ordenada
            echo "</div>";
        }
        ?>

                            </div>
                        </div>


                        <div class="inputBox">
                            <label for="fotoprin" class="labelInput">Imagem do perfil:</label>
                            <p><br><br>
                                <input type="file" class="fileInput" name="fotoprin" id="fotoprin"
                                    data-titulo="Imagem" data-obrigatorio="1" accept="image/*">
                                <label for="fotoprin" class="fileInputLabel">Escolher arquivo</label>
                                <span id="arquivo_selecionado_perfil"></span>
                            </p>

                        </div>



                        <div class="inputBox">
                        <label for="field_conteudo" class="labelInput">Fale um pouco sobre você ou sobre o seu negócio:</label><br>
                        <textarea class="descricao" id="field_conteudo" name="descricao" rows="6"></textarea>
                        </div>

                        <div class="inputBox">
                            <label for="fotosec" class="labelInput">Envie fotos do seu trabalho aqui
                                (opcional):</label>
                            <p><br><br>
                                <input type="file" class="fileInput" name="fotosec" id="fotosec"
                                    data-titulo="Imagem" accept="image/*">
                                <label for="fotosec" class="fileInputLabel">Escolher arquivo</label>
                                <span id="arquivo_selecionado_trabalho1"></span>
                            </p>
                        </div>

                        <div class="inputBox">
                            <label for="fotosec2" class="labelInput">Envie mais uma foto do seu trabalho
                                (opcional):</label>
                            <p><br><br>
                                <input type="file" class="fileInput" name="fotosec2" id="fotosec2"
                                    data-titulo="Imagem" accept="image/*">
                                <label for="fotosec2" class="fileInputLabel">Escolher arquivo</label>
                                <span id="arquivo_selecionado_trabalho2"></span>
                            </p>
                        </div>

                        <div class="inputBox">
                            <label for="status" class="labelInput">Status</label><br>
                            <div class="select-wrapper">
                                <select name="status" id="status" class="inputUser">
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                                <span class="select-icon"></span>
                            </div>
                        </div>
                        <br><br>


                    </fieldset>
                    <div class="btn_alinhamento">
                        <button type="submit" id="submit" value="Enviar" name="salvar">Enviar</button>
                        </a>
                        <a href="gerenciamento_admin_add.php">
                            <button type="button" id="cancel" value="Cancelar" name="cancelar">Cancelar</button>
                        </a>
                    </div>
                </form>
            </section>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js">
        //biblioteca do JavaScript(necessário pra rodar códigos .js)
        </script>

        <script>
        var checkboxes = document.querySelectorAll('input[type=checkbox][name="servico[]"]');
        var maxServicos = 6;

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var checkedCount = document.querySelectorAll(
                    'input[type=checkbox][name="servico[]"]:checked').length;
                if (checkedCount > maxServicos) {
                    checkbox.checked = false;
                }
            });
        });
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
        //exibe o nome do arquivo selecionado nos campos de imagem
        </script>


    </main>

</body>


</html>