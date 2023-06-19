<?php
// Iniciando a sessão
session_start();

// Incluindo o arquivo de cabeçalho
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once "../database/conexao.php";

// Verificando se o usuário está logado como cliente
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'CLI' && $_SESSION['usuario']['perfil'] != 'ADM')) {
    header("Location: index.php?error=Você precisa estar logado como cliente para ter acesso a este recurso!");
    exit;
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $pontuacao = $_POST['pontuacao'];
    $comentario = $_POST['comentario'];
    $idpro = isset($_POST['idpro']) ? $_POST['idpro'] : 0;
    $idcli = $_SESSION['usuario']['idcli'];


    // Cria a conexão com o banco de dados
    $dbh = Conexao::getInstance();

    // Insere a avaliação no banco de dados
    $query = "INSERT INTO `busca_service`.`avaliacao` (idpro, idcli, pontuacao, comentario) VALUES (:idpro, :idcli, :pontuacao, :comentario)";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':idpro', $idpro, PDO::PARAM_INT);
    $stmt->bindParam(':idcli', $idcli);
    $stmt->bindParam(':pontuacao', $pontuacao);
    $stmt->bindParam(':comentario', $comentario);

    $idpro_encrypted = base64_encode($idpro);

    if ($stmt->execute()) {
        $url = "perfil_exibe_pro.php?idpro=" . urlencode($idpro_encrypted) . "&success=Avaliação cadastrada com sucesso";
        header('Location: ' . $url);
    } else {
        $url = "perfil_exibe_pro.php?idpro=" . urlencode($idpro_encrypted) . "&error=Erro ao enviar avaliação";
        header('Location: ' . $url);
    }


    $dbh = null;
}
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