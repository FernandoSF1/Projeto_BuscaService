<?php
session_start();

require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once "../database/conexao.php";

?>


<?php
$dbh = Conexao::getInstance();

// Obtém o id do cliente da sessão ou do parâmetro GET criptografado
if (isset($_SESSION['idcli'])) {
    $idcli = $_SESSION['idcli'];
} elseif (isset($_GET['idcli'])) {
    $idcli = base64_decode($_GET['idcli']);
} else {
    $idcli = 0;
}

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
            // Obtém o ID do cliente criptografado
            $idcliCriptografado = base64_encode($idcli);
            header('location: historico_ava.php?idcli=' . $idcliCriptografado . '&success=Avaliação excluída com sucesso!');
            exit();
        } else {
            // Obtém o ID do cliente criptografado
            $idcliCriptografado = base64_encode($idcli);
            header('location: historico_ava.php?idcli=' . $idcliCriptografado . '&error=Erro ao excluir a avaliação!');
            exit();
        }
    }
}
?>

<?php
$query = "SELECT a.*, c.nome AS nome_cliente, p.titulo AS titulo_negocio 
              FROM `busca_service`.`avaliacao` AS a
              JOIN `busca_service`.`cliente` AS c ON a.idcli = c.idcli
              JOIN `busca_service`.`profissional` AS p ON a.idpro = p.idpro
              WHERE a.idcli=:idcli
              ORDER BY a.data DESC"; // Ordena pela coluna de data em ordem decrescente

$stmt = $dbh->prepare($query);
$stmt->bindParam(':idcli', $idcli);
$stmt->execute();

// Verifica se existem avaliações
if ($stmt->rowCount() > 0) {
    echo '<div class="historico-avaliacoes">';
    echo '<h2 class="historico-avaliacoes-titulo">Histórico de avaliações enviadas</h2>';

    while ($avaliacao = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Criptografa o ID do profissional
        $idproCriptografado = base64_encode($avaliacao['idpro']);

        // Exibe as informações da avaliação
        echo '<div class="avaliacao-item">';
        echo '<p class="avaliacao-negocio">Perfil do profissional: <a class="avaliacao-link" href="perfil_exibe_pro.php?idpro=' . $idproCriptografado . '">' . $avaliacao['titulo_negocio'] . '</a></p>';
        echo '<p class="avaliacao-nome">' . $avaliacao['nome_cliente'] . '</p>';


        // Formata a data no formato dd/mm/aaaa
        $dataFormatada = date('d/m/Y', strtotime($avaliacao['data']));
        // Formata a hora no formato hh:mm
        $horaFormatada = date('H:i', strtotime($avaliacao['data']));

        echo '<p class="avaliacao-data">' . $dataFormatada . ' às ' . $horaFormatada . '</p>';

        echo '<div class="avaliacao-estrelas">';
        echo '<span class="avaliacao-pontuacao">';

        echo '<div class="estrelas">';

        // Exibe as estrelas correspondentes à pontuação
        for ($i = 1; $i <= 5; $i++) {
            $starImage = ($i <= $avaliacao['pontuacao']) ? 'assets/img/estrela.png' : 'assets/img/estrela_vazia.png';
            echo '<img src="' . $starImage . '" alt="Estrela" style="width: 25px; height: 25px;">';
        }

        echo '</div>';

        echo '</span>';
        echo '</div>';
        echo '<p class="avaliacao-comentario">' . $avaliacao['comentario'] . '</p>';

        // Botões de editar e apagar
        echo '<div class="avaliacao-botoes">';
        echo '<button class="perfil-btn" name="botao" id="edit-cli-ava' . $avaliacao['idava'] . '" value="editar">Editar avaliação</button>';
        echo '<form method="POST" onsubmit="return confirm(\'Deseja excluir a avaliação?\');" action="">';
        echo '<input type="hidden" name="idava" value="' . $avaliacao['idava'] . '">';
        echo '<button class="perfil-btn" name="botao" id="delete-cli-ava" value="deletar">Excluir avaliação</button>';
        echo '</form>';
        echo '</div>';

        echo '</div>';
    }

    echo '</div>'; // Fecha a div "historico-avaliacoes"
} else {
    echo '<p>Nenhuma avaliação encontrada.</p>';
}
?>

<script>
    <?php
    $errorMessage = isset($_GET['error']) ? $_GET['error'] : '';
    $successMessage = isset($_GET['success']) ? $_GET['success'] : '';

    if (!empty($errorMessage)) {
        echo "Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: '{$errorMessage}'
        });";
    }

    if (!empty($successMessage)) {
        echo "Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: '{$successMessage}'
        });";
    }
    ?>
</script>

<?php require_once 'layouts/site/footer.php'; ?>
