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

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

# verifica se os dados do formulario foram enviados via POST 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # recupera o id do enviado por post para delete ou update.
    $idava = (isset($_POST['idava']) ? $_POST['idava'] : 0);
    $operacao = (isset($_POST['botao']) ? $_POST['botao'] : null);
    # verifica se o nome do botão acionado por post se é deletar ou atualizar
    if ($operacao === 'deletar') {
        # cria uma query no banco de dados para excluir o usuario com id informado 
        $query = "DELETE FROM `busca_service`.`avaliacao` WHERE idava = :idava";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':idava', $idava);

        # executa a consulta banco de dados para excluir o registro.
        $stmt->execute();

        # verifica se a quantiade de registros excluido é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de admin com mensagem de erro.
        if ($stmt->rowCount()) {
            header('location: usuario_admin_listava.php?success=Avaliação excluída com sucesso!');
        } else {
            header('location: usuario_admin_listava.php?error=Erro ao excluir a avaliação!');
        }
    }
}

# cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
# ordenando pelo campo perfil e nome.
$query = "SELECT a.*, c.nome AS nome_cli, p.titulo
          FROM avaliacao a
          INNER JOIN cliente c ON a.idcli = c.idcli
          INNER JOIN profissional p ON a.idpro = p.idpro
          ORDER BY p.titulo, a.data";

$stmt = $dbh->prepare($query);

# executa a consulta banco de dados e aguarda o resultado.
$stmt->execute();

# Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
# se não existir retorna null
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo"<pre>";var_dump($rows);exit;
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
            <div class="main_stage">
                <div class="main_stage_content">

                    <article>
                        <header>

                            <table border="0" width="1300px" class="table">

                                <tr>
                                    <th>#</th>
                                    <th>Nome do cliente</th>
                                    <th>Perfil do profissional avaliado</th>
                                    <th>Comentário</th>
                                    <th>Pontuação</th>
                                    <th>Data de envio</th>
                                    <th>Ação</th>
                                </tr>

                                <?php
                                # verifica se os dados existem na variavel $row.
                                # se existir faz um loop nos dados usando foreach.
                                # cria uma variavel $count para contar os registros da tabela.
                                # se não existir vai para o else e imprime uma mensagem.
                                if ($rows) {
                                    $count = 1;
                                    foreach ($rows as $row) { ?>
                                        <tr>
                                            <td><?= $count ?></td>
                                            <td><?= $row['nome_cli'] ?></td>
                                            <td><a href="perfil_exibe_pro.php?idpro=<?= base64_encode($row['idpro']) ?>" class="titulo_pro_link"><?= $row['titulo'] ?></a></td>
                                            <td><?= $row['comentario'] ?></td>
                                            <td><?= $row['pontuacao'] ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($row['data'])) ?></td>

                                            <td>
                                            <a href="usuario_admin_updava.php?idava=<?= base64_encode($row['idava']) ?>" class="btn" id="edit">Editar</a>
                                                &nbsp;
                                                <form action="" method="post">
                                                    <input type="hidden" name="idava" value="<?= $row['idava'] ?>" />
                                                    <button class="btn" name="botao" id="delete" value="deletar" onclick="return confirm('Deseja realmente excluir esta avaliação?');">Apagar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php $count++;
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="6"><strong>Não existem usuários cadastrados.</strong></td>
                                    </tr>
                                <?php } ?>
                            </table>

                        </header>
                    </article>
                    <article>
                        <header>
                            <h2><a href="gerenciamento_admin_list.php" class="btn_volta">Voltar</a></h2>
                        </header>
                    </article>
                </div>
            </div>

    </main>
    <!--FIM DOBRA PALCO PRINCIPAL-->

</body>


</html>