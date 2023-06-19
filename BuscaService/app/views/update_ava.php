<?php
session_start();
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
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

$idavaCriptografado = isset($_GET['idava']) ? $_GET['idava'] : '';
$idava = base64_decode(urldecode($idavaCriptografado));
//var_dump($idava);
$idproCriptografado = isset($_GET['idpro']) ? $_GET['idpro'] : '';
$idpro = base64_decode(urldecode($idproCriptografado));
//var_dump($idava,$idpro);

$dbh = Conexao::getInstance();

// Buscar a pontuação da última avaliação do cliente para o id do profissional
$query = "SELECT * FROM `busca_service`.`avaliacao` WHERE idava=:idava";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':idava', $idava);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
//echo '<pre>'; var_dump($row); exit;


if ($row === false) {
    // A consulta não retornou resultados, faça algo para lidar com isso
    header('location: update_ava.php?error=Erro ao obter a avaliação!');
    exit;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pontuacao = isset($_POST['pontuacao']) ? $_POST['pontuacao'] : '';
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';

    $query = "UPDATE `busca_service`.`avaliacao` SET `pontuacao` = :pontuacao, `comentario` = :comentario WHERE idava = :idava";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':pontuacao', $pontuacao);
    $stmt->bindParam(':comentario', $comentario);
    $stmt->bindParam(':idava', $idava);


    $stmt->execute();

    if ($stmt->rowCount()) {
        $idcliCriptografado = base64_encode($_SESSION['usuario']['idcli']);
        header('location: historico_ava.php?idcli=' . urlencode($idcliCriptografado) . '&success=Avaliação atualizada com sucesso!');
        exit;
    } else {
        $error = $dbh->errorInfo();
        var_dump($error);
        header('location: update_ava.php?idava=' . urlencode($idavaCriptografado) . '&idpro=' . urlencode($idproCriptografado) . '&error=Erro ao atualizar a avaliação!');
        exit;
    }
}



$pontuacaoAvaAtual = $row['pontuacao'];
//echo '<pre>'; var_dump($row); exit;
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
                <form action="update_ava.php?idava=<?php echo urlencode($idavaCriptografado); ?>&idpro=<?php echo urlencode($idproCriptografado); ?>" method="post" class="box">

                    <fieldset>
                        <legend><b>Atualizar Avaliação</b></legend>
                        <div class="avaliacoes">
                            <h2>Nota atual da avaliação: <?php echo $pontuacaoAvaAtual; ?></h2>
                            <div class="estrelas_alinhamento">
                            <?php
                            $estrelaPreenchida = 'assets/img/estrela.png';
                            $estrelaVazia = 'assets/img/estrela_vazia2.png';

                            for ($i = 1; $i <= 5; $i++) {
                                $imagemEstrela = ($i <= $pontuacaoAvaAtual) ? $estrelaPreenchida : $estrelaVazia;
                                echo '<img src="' . $imagemEstrela . '" alt="Estrela" style="width: 25px; height: 25px;">';
                            }
                            ?>
                            </div>
                            <div class="avaliacao-form">
                                <h2>Alterar avaliação</h2>
                                <div class="campo-avaliacao">
                                    <label for="pontuacao" class="label_perfil">Pontuação:</label>
                                    <div class="estrelas">
                                        <input type="radio" id="pontuacao1" name="pontuacao" value="1" required <?php if ($row['pontuacao'] == '1') echo 'checked'; ?>>
                                        <label for="pontuacao1" onclick="marcarEstrelas(1)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                        <input type="radio" id="pontuacao2" name="pontuacao" value="2" required <?php if ($row['pontuacao'] == '2') echo 'checked'; ?>>
                                        <label for="pontuacao2" onclick="marcarEstrelas(2)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                        <input type="radio" id="pontuacao3" name="pontuacao" value="3" required <?php if ($row['pontuacao'] == '3') echo 'checked'; ?>>
                                        <label for="pontuacao3" onclick="marcarEstrelas(3)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                        <input type="radio" id="pontuacao4" name="pontuacao" value="4" required <?php if ($row['pontuacao'] == '4') echo 'checked'; ?>>
                                        <label for="pontuacao4" onclick="marcarEstrelas(4)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                        <input type="radio" id="pontuacao5" name="pontuacao" value="5" required <?php if ($row['pontuacao'] == '5') echo 'checked'; ?>>
                                        <label for="pontuacao5" onclick="marcarEstrelas(5)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                                    </div>
                                </div>
                                <div class="campo-avaliacao">
                                    <label for="comentario" class="label_perfil">Comentário:</label>
                                    <textarea id="comentario" name="comentario" class="input_perfil"><?php echo $row['comentario']; ?></textarea>
                                </div>

                                <script>
                                    function marcarEstrelas(pontuacao) {
                                        var estrelas = document.querySelectorAll('.estrelas label img');

                                        for (var i = 0; i < estrelas.length; i++) {
                                            if (i < pontuacao) {
                                                estrelas[i].src = 'assets/img/estrela.png';
                                            } else {
                                                estrelas[i].src = 'assets/img/estrela_vazia2.png';
                                            }
                                        }
                                    }
                                </script>
                            </div><br><br>


                    </fieldset>
                    <div class="btn_alinhamento">
                        <a href="historico_ava.php">
                            <button type="submit" id="submit" value="Enviar" name="salvar" onclick="return confirm('Deseja realmente alterar esta avaliação?');">Enviar</button>
                        </a>
                        <a href="historico_ava.php?idcli=<?= base64_encode($idcli) ?>">
                            <button type="button" id="cancel" value="Cancelar" name="cancelar">Cancelar</button>
                        </a>

                    </div>
                </form>
            </section>
        </div>
        <br><br>
    </main>

</body>

</html>