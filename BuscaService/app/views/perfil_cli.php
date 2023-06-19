<?php
// Iniciando a sessão
session_start();

// Incluindo o arquivo de cabeçalho
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como cliente
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'CLI') {
    header("Location: index.php?error=Você precisa estar logado como cliente para ter acesso a este recurso");
    exit;
}

# usa o ID do cliente que ficou armazenado na sessão, após o login
$idcli = isset($_SESSION['usuario']['idcli']) ? $_SESSION['usuario']['idcli'] : 0;

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

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
    header('location: index.php?error=Usuário inválido.');
}

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # recupera o id do enviado por post para delete ou update.
    $idcli = (isset($_POST['idcli']) ? $_POST['idcli'] : 0);
    $operacao = (isset($_POST['botao']) ? $_POST['botao'] : null);
    # verifica se o nome do botão acionado por post se é deletar ou atualizar
    if ($operacao === 'deletar') {
        # cria uma query no banco de dados para excluir o usuario com id informado 
        $query = "DELETE FROM `busca_service`.`cliente` WHERE idcli = :idcli";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idcli', $idcli);

        # executa a consulta banco de dados para excluir o registro.
        $stmt->execute();

        # verifica se a quantiade de registros excluido é maior que zero.
        # se sim, redireciona para a pagina index com mensagem de sucesso.
        # se não, redireciona para a pagina perfil_cli com mensagem de erro.
        if ($stmt->rowCount()) {
            # destroi todas sessões, se existirem.
            session_destroy();
            header('location: index.php?success=Conta excluída com sucesso!');
        } else {
            header('location: perfil_cli.php?error=Erro ao excluir a sua conta!');
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
        <h1 class="container_titulo">Perfil do Cliente</h1>

        <!-- Botão: Editar dados -->    
        <a href="update_cli.php?idcli=<?= base64_encode($row['idcli']) ?>">
        <button class="perfil-btn" name="botao" id="edit-perfil" value="editar">Editar meus dados</button>
        </a>

        <!-- Botão: Apagar conta -->
        <form action="" method="post">
            <input type="hidden" name="idcli" value="<?= $row['idcli'] ?>" />
            <button class="perfil-btn" name="botao" id="delete-perfil" value="deletar" onclick="return confirm('Deseja realmente excluir sua conta?');">Excluir minha conta</button>
        </form>

        <div class="dados">
            <div class="dados-pessoais_cli">
                <div class="campo campo-pessoal">
                    <label for="nome" class="label_perfil">Nome:</label>
                    <input type="text" id="nome" class="input_perfil" value="<?= $row['nome'] ?>" readonly>
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
                    <label for="dataregcli" class="label_perfil">Data de Registro:</label>
                    <input type="text" id="data_registro" class="input_perfil" value="<?= date('d/m/Y', strtotime($row['dataregcli'])) ?>" readonly>
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

        <!-- Tópico: Histórico de avaliações enviadas -->
        <div class="historico-avaliacoes">
            <h2 class="imagens_trabalho_titulo">Histórico de avaliações enviadas</h2>
            <a href="historico_ava.php?idcli=<?= base64_encode($row['idcli']) ?>" class="btn_historico">Ver avaliações</a>
        </div>
        <!-- Tópico: Histórico de pagamento -->
        <div class="historico-avaliacoes">
            <h2 class="imagens_trabalho_titulo">Pagamento</h2>
            <!-- Declaração do formulário -->
            <form method="post" target="pagseguro" action="https://pagseguro.uol.com.br/v2/checkout/payment.html">

                <!-- Campos obrigatórios -->
                <input name="receiverEmail" type="hidden" value="suporte@lojamodelo.com.br">
                <input name="currency" type="hidden" value="BRL">

                <!-- Itens do pagamento (ao menos um item é obrigatório) -->
                <input name="itemId1" type="hidden" value="0001">
                <input name="itemDescription1" type="hidden" value="Azulejista">
                <input name="itemAmount1" type="hidden" value="150.00">
                <input name="itemQuantity1" type="hidden" value="1">
                <input name="itemWeight1" type="hidden" value="1000">
                <input name="itemId2" type="hidden" value="0002">
                <input name="itemDescription2" type="hidden" value="Pedreiro">
                <input name="itemAmount2" type="hidden" value="250.00">
                <input name="itemQuantity2" type="hidden" value="1">
                <input name="itemWeight2" type="hidden" value="750">

                <!-- Código de referência do pagamento no seu sistema (opcional) -->
                <input name="reference" type="hidden" value="REF1234">

                <!-- Informações de frete (opcionais) -->
                <input name="shippingType" type="hidden" value="1">
                <input name="shippingAddressPostalCode" type="hidden" value="01452002">
                <input name="shippingAddressStreet" type="hidden" value="Av. Brig. Faria Lima">
                <input name="shippingAddressNumber" type="hidden" value="1384">
                <input name="shippingAddressComplement" type="hidden" value="5o andar">
                <input name="shippingAddressDistrict" type="hidden" value="Jardim Paulistano">
                <input name="shippingAddressCity" type="hidden" value="Sao Paulo">
                <input name="shippingAddressState" type="hidden" value="SP">
                <input name="shippingAddressCountry" type="hidden" value="BRA">

                <!-- Dados do comprador (opcionais) -->
                <input name="senderName" type="hidden" value="José Comprador">
                <input name="senderAreaCode" type="hidden" value="11">
                <input name="senderPhone" type="hidden" value="56273440">
                <input name="senderEmail" type="hidden" value="comprador@uol.com.br">

                <!-- submit do form (obrigatório) -->
                <input alt="Pague com PagSeguro" name="submit" type="image" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/pagamentos/120x53-pagar.gif" />
            </form>
        </div>
    </div>
</body>

<!--INÍCIO DOBRA RODAPÉ-->

<!-- inclui o arquivo de rodape do site -->
<?php require_once 'layouts/site/footer.php'; ?>