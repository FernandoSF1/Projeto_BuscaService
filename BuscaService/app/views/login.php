<?php
# inclui a classe de conexao com o banco de dados.
require_once "../database/conexao.php";

# verifica se os dados do formulario foram passados via método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria duas variaveis (nome, password) para armazenar os dados passados via método POST.
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $password = isset($_POST['senha']) ? md5($_POST['senha']) : '';

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # cria uma consulta banco de dados verificando se o usuario existe na tabela "cliente"
    $queryCliente = "SELECT * FROM `busca_service`.`cliente` WHERE nome = :nome AND `senha` = :senha";
    $stmtCliente = $dbh->prepare($queryCliente);
    $stmtCliente->bindParam(':nome', $nome);
    $stmtCliente->bindParam(':senha', $password);
    $stmtCliente->execute();
    $rowCliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    # cria uma consulta banco de dados verificando se o usuario existe na tabela "profissional"
    $queryProfissional = "SELECT * FROM `busca_service`.`profissional` WHERE nome = :nome AND `senha` = :senha";
    $stmtProfissional = $dbh->prepare($queryProfissional);
    $stmtProfissional->bindParam(':nome', $nome);
    $stmtProfissional->bindParam(':senha', $password);
    $stmtProfissional->execute();
    $rowProfissional = $stmtProfissional->fetch(PDO::FETCH_ASSOC);

    # verifica se o usuário foi encontrado na tabela "cliente" ou na tabela "profissional"
    if ($rowCliente) {

        // Usuário encontrado na tabela "cliente"
        $_SESSION['usuario'] = [
            'nome' => $rowCliente['nome'],
            'perfil' => $rowCliente['perfil'],
            'idcli' => $rowCliente['idcli'], // Adicione o ID do cliente à sessão
        ];
        if ($rowCliente['perfil'] === 'ADM') {
            header('location: usuario_admin.php');
        } else {
            header('location: index.php');
        }
    } elseif ($rowProfissional) {
        
        // Usuário encontrado na tabela "profissional"
        $_SESSION['usuario'] = [
            'nome' => $rowProfissional['nome'],
            'perfil' => $rowProfissional['perfil'],
            'idpro' => $rowProfissional['idpro'], // Adicione o ID do profissional à sessão
        ];
        if ($rowProfissional['perfil'] === 'ADM') {
            header('location: usuario_admin.php');
        } else {
            header('location: index.php');
        }
    } else {
        // Usuário não encontrado em nenhuma das tabelas
        session_destroy();
        header('location: index.php?error=Usuário ou senha inválidos.');
    }

    # destroi a conexao com o banco de dados.
    $dbh = null;
}

?>
<!--POP LOGIN-->
<div class="overlay"></div>
<div class="modal">

    <div class="div_login">
        <form action="index.php" method="post">
            <h1>Login</h1><br>
            <input type="text" name="nome" placeholder="Nome" class="input" required autofocus>
            <br><br>
            <input type="password" name="senha" placeholder="Senha" class="input" required>
            <br><br>
            <button class="button">Enviar</button>
        </form>
        <h2><a href="#">Esqueci minha senha</a></h2><br>
        <h2><a href="#">Não tem uma conta? Registre-se agora mesmo!</a></h2>
    </div>

</div>
<!--FIM POP LOGIN-->
