<?php
# inclui a classe de conexão com o banco de dados.
require_once "../database/conexao.php";

# verifica se os dados do formulário foram passados via método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # cria duas variáveis (email, password) para armazenar os dados passados via método POST.
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['senha']) ? md5($_POST['senha']) : '';

    # cria a variável $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # cria uma consulta no banco de dados verificando se o usuário existe na tabela "cliente"
    $queryCliente = "SELECT * FROM `busca_service`.`cliente` WHERE email = :email AND `senha` = :senha";
    $stmtCliente = $dbh->prepare($queryCliente);
    $stmtCliente->bindParam(':email', $email);
    $stmtCliente->bindParam(':senha', $password);
    $stmtCliente->execute();
    $rowCliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

    # cria uma consulta no banco de dados verificando se o usuário existe na tabela "profissional"
    $queryProfissional = "SELECT * FROM `busca_service`.`profissional` WHERE email = :email AND `senha` = :senha";
    $stmtProfissional = $dbh->prepare($queryProfissional);
    $stmtProfissional->bindParam(':email', $email);
    $stmtProfissional->bindParam(':senha', $password);
    $stmtProfissional->execute();
    $rowProfissional = $stmtProfissional->fetch(PDO::FETCH_ASSOC);

    # verifica se o usuário foi encontrado na tabela "cliente" ou na tabela "profissional"
    if ($rowCliente) {

        // Usuário encontrado na tabela "cliente"
        $_SESSION['usuario'] = [
            'email' => $rowCliente['email'],
            'perfil' => $rowCliente['perfil'],
            'idcli' => $rowCliente['idcli'], // Adicione o ID do cliente à sessão
            'nome' => $rowCliente['nome'] // Adicione o nome do cliente à sessão
        ];
        if ($rowCliente['perfil'] === 'ADM') {
            header('location: usuario_admin.php');
        } else {
            header('location: index.php');

        }
    } elseif ($rowProfissional) {

        // Usuário encontrado na tabela "profissional"
        $_SESSION['usuario'] = [
            'email' => $rowProfissional['email'],
            'perfil' => $rowProfissional['perfil'],
            'idpro' => $rowProfissional['idpro'], // Adicione o ID do profissional à sessão
            'nome' => $rowProfissional['nome'] // Adicione o nome do profissional à sessão
        ];
        if ($rowProfissional['perfil'] === 'ADM') {
            header('location: usuario_admin.php');
        } else {
            header('location: index.php');
        }
    } else {
        // Usuário não encontrado em nenhuma das tabelas
        session_destroy();
        header('location: index.php?error=Usuário ou senha inválidos');
    }

    # destroi a conexão com o banco de dados.
    $dbh = null;
}

?>
<!--POP LOGIN-->
<div class="overlay"></div>
<div class="modal">

    <div class="div_login">
        <form action="index.php" method="post">
            <h1>Login</h1><br>
            <input type="email" name="email" placeholder="Digite seu e-mail" class="input" required autofocus>
            <br><br>
            <input type="password" name="senha" placeholder="Senha" class="input" required>
            <br><br>
            <button class="button">Fazer login</button>
        </form>
    </div>

</div>
<!--FIM POP LOGIN-->