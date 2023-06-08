<?php
session_start();
require_once 'layouts/site/header.php';
require_once "../database/conexao.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'CLI') {
    header("Location: index.php?error=Você precisa estar logado como cliente para ter acesso a este recurso");
    exit;
}

if (isset($_SESSION['usuario']['idcli'])) {
    $idcli = $_SESSION['usuario']['idcli'];
} else {
    if (isset($_GET['idcli'])) {
        $idcli = base64_decode($_GET['idcli']);
    } else {
        $idcli = 0;
    }
}

$dbh = Conexao::getInstance();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pontuacao = isset($_POST['pontuacao']) ? $_POST['pontuacao'] : '';
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';

    $query = "UPDATE `busca_service`.`avaliacao` SET `pontuacao` = :pontuacao, `comentario` = :comentario WHERE idcli = :idcli";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':pontuacao', $pontuacao);
    $stmt->bindParam(':comentario', $comentario);
    $stmt->bindParam(':idcli', $idcli);

    $stmt->execute();

    if ($stmt->rowCount()) {
        header('location: historico_ava.php?success=Avaliação atualizada com sucesso!');
    } else {
        $error = $dbh->errorInfo();
        var_dump($error);
        header('location: update_ava.php?error=Erro ao atualizar a avaliação!');
    }

    $dbh = null;
}

$query = "SELECT * FROM `busca_service`.`avaliacao` WHERE idcli=:idcli LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':idcli', $idcli);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//echo '<pre>'; var_dump($row); exit;

if (!$row) {
    header('location: perfil_cli.php?error=Usuário inválido.');
}

$dbh = null;
?>

<body>
    <?php require_once 'layouts/admin/menu.php'; ?>
    <main class="bg_form">
        <div class="main_opc">
            <?php
            if (isset($_GET['error'])) { ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Cliente',
                        text: '<?= $_GET['error'] ?>',
                    })
                </script>
            <?php } ?>
            <section>
                <form action="update_ava.php" method="post" class="box">
                    <fieldset>
                        <legend><b>Atualizar Avaliação</b></legend>
                        <div class="avaliacoes">
            <h2>Avaliações</h2>

            <?php
            # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
            $dbh = Conexao::getInstance();
            // Consulta as avaliações do profissional, incluindo o nome do cliente
            $query = "SELECT a.*, c.nome AS nome_cliente FROM `busca_service`.`avaliacao` AS a
          JOIN `busca_service`.`cliente` AS c ON a.idcli = c.idcli
          WHERE a.idpro=:idpro";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':idpro', $idpro);
            $stmt->execute();

            // Verifica se existem avaliações
            if ($stmt->rowCount() > 0) {
                while ($avaliacao = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Exibe as informações da avaliação
                    echo '<div class="avaliacao-item">';
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
                        echo '<img src="' . $starImage . '" style="width: 25px; height: 25px;">';
                    }
                    echo '</div>';

                    echo '</span>';
                    echo '</div>';
                    echo '<p class="avaliacao-comentario">' . $avaliacao['comentario'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhuma avaliação encontrada.</p>';
            }
            ?>

                        <div class="endereco">
                            <div class="avaliacao-form">
                                <h2>Fazer uma Avaliação</h2>
                                <form action="cadastra_avaliacao.php" method="POST">
                                    <div class="campo-avaliacao">
                                        <label for="pontuacao" class="label_perfil">Pontuação:</label>
                                        <div class="estrelas">
                                            <input type="radio" id="pontuacao1" name="pontuacao" value="1" required <?php if ($row['pontuacao'] == '1') echo 'checked'; ?>>
                                            <label for="pontuacao1" onclick="marcarEstrelas(1)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                            <input type="radio" id="pontuacao2" name="pontuacao" value="2" required <?php if ($row['pontuacao'] == '2') echo 'checked'; ?>>
                                            <label for="pontuacao2" onclick="marcarEstrelas(2)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                            <input type="radio" id="pontuacao3" name="pontuacao" value="3" required <?php if ($row['pontuacao'] == '3') echo 'checked'; ?>>
                                            <label for="pontuacao3" onclick="marcarEstrelas(3)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                            <input type="radio" id="pontuacao4" name="pontuacao" value="4" required <?php if ($row['pontuacao'] == '4') echo 'checked'; ?>>
                                            <label for="pontuacao4" onclick="marcarEstrelas(4)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                            <input type="radio" id="pontuacao5" name="pontuacao" value="5" required <?php if ($row['pontuacao'] == '5') echo 'checked'; ?>>
                                            <label for="pontuacao5" onclick="marcarEstrelas(5)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                            <input type="hidden" name="idpro" value="<?php echo $row['idpro']; ?>">
                                        </div>
                                    </div>
                                    <div class="campo-avaliacao">
                                        <label for="comentario" class="label_perfil">Comentário:</label>
                                        <textarea id="comentario" name="comentario" class="input_perfil"><?php echo $row['comentario']; ?></textarea>
                                        <input type="hidden" name="idpro" value="<?= $idpro ?>">
                                    </div>
                                    <input type="submit" value="Enviar Avaliação">
                                </form>
                            </div>

                            <script>
                                function marcarEstrelas(pontuacao) {
                                    var estrelas = document.querySelectorAll('.estrelas label img');

                                    for (var i = 0; i < estrelas.length; i++) {
                                        if (i < pontuacao) {
                                            estrelas[i].src = 'assets/img/estrela.png';
                                        } else {
                                            estrelas[i].src = 'assets/img/estrela_vazia.png';
                                        }
                                    }
                                }
                            </script><br><br>
                        </div>


                    </fieldset>
                    <div class="btn_alinhamento">
                        <button type="submit" id="submit" value="Enviar" name="salvar" onclick="return confirm('Deseja realmente alterar a avaliação?');">Enviar</button>
                        <a href="historico_ava.php">
                            <button type="button" id="cancel" value="Cancelar" name="cancelar">Cancelar</button>
                        </a>
                    </div>
                </form>
            </section>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </main>

</body>

</html>
