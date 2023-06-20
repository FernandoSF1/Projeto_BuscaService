<?php
// Iniciando a sessão
session_start();

// Incluindo os arquivos de cabeçalho
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
require_once "../database/conexao.php";

$idpro_encrypted = isset($_GET['idpro']) ? $_GET['idpro'] : '';
$idpro = base64_decode(urldecode($idpro_encrypted));

// Resto do código...


# cria a variável $dbh que vai receber a conexão com o SGBD e banco de dados
$dbh = Conexao::getInstance();

# cria uma consulta banco de dados buscando todos os dados da tabela profissional
# filtrando pelo id do profissional
$query = "SELECT * FROM `busca_service`.`profissional` WHERE idpro=:idpro LIMIT 1";

try {
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':idpro', $idpro);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //echo '<per>';var_dump($row);exit;
} catch (PDOException $e) {
    // Tratar o erro do primeiro fetch
    echo "Erro no primeiro fetch: " . $e->getMessage();
    die(); // Interrompe a execução após exibir a mensagem de erro
}

# destroi a conexao com o banco de dados
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
        <h1 class="container_titulo">Perfil do Profissional</h1>

        <div class="dados">
            <div class="dados-pessoais_cli">

                <div class="campo campo-pessoal">
                    <img src="<?= $row['fotoprin'] ?>" class="imagens_perfil" alt="Imagem perfil">
                </div>

                <div class="campo campo-pessoal">
                    <p class="campo_titulo_perfil"><?php echo $row['titulo']; ?></p>
                </div>

                <div class="campo campo-pessoal">
                    <label for="nome" class="label_perfil">Nome do profissional:</label>
                    <p class="dado_pessoal_perfil"><?= $row['nome'] ?></p>
                </div>

                <div class="campo campo-pessoal">
                    <label for="nome_servico" class="label_perfil">Serviço(s) que este profissional oferece:</label>
                    <p class="dado_pessoal_perfil servicos-oferecidos-perfil">
                        <?php
                        $dbh = Conexao::getInstance();
                        $queryServicos = "SELECT s.nome FROM servico s INNER JOIN profissional_has_servico ps ON s.idserv = ps.idserv WHERE ps.idpro = :idpro ORDER BY s.nome ASC";
                        $stmtServicos = $dbh->prepare($queryServicos);
                        $stmtServicos->bindParam(':idpro', $idpro);
                        $stmtServicos->execute();
                        $servicos = $stmtServicos->fetchAll(PDO::FETCH_COLUMN);
                        $totalServicos = count($servicos);
                        foreach ($servicos as $index => $servico) {
                            echo '<span class="destaque-servico">' . $servico . '</span>';
                            if ($index < $totalServicos - 1) {
                                echo ', ';
                            }
                        }
                        ?>
                    </p>
                </div>

                <div class="campo campo-pessoal">
                    <h2 class="descricao_titulo">Sobre <span class="titulo_descricao_perfil"><?php echo "&nbsp;" . $row['titulo']; ?></span>:</h2>
                    <div class="descricao_negocio">
                        <p><?php echo $row['descricaonegocio']; ?></p>
                    </div>
                </div>
            </div>

            <div class="dados dados-endereco">
                <div class="campo campo-endereco">
                    <label for="estado" class="label_perfil">Estado:</label>
                    <p class="dado_pessoal_perfil"><?= $row['estado'] ?></p>
                </div>

                <div class="campo campo-endereco">
                    <label for="cidade" class="label_perfil">Cidade:</label>
                    <p id="nome" class="dado_pessoal_perfil"><?= $row['cidade'] ?></p>
                </div>

                <div class="campo campo-endereco">
                    <label for="bairro" class="label_perfil">Bairro:</label>
                    <p id="nome" class="dado_pessoal_perfil"><?= $row['bairro'] ?></p>
                </div>

                <div class="campo campo-pessoal">
                    <label for="telefone" class="label_perfil">Telefones de Contato:</label>
                    <input type="text" id="telefone" class="input_perfil" value="<?= $row['telefone'] ?>" readonly>
                </div>

                <div class="campo campo-pessoal">
                    <input type="text" id="telefone2" class="input_perfil" value="<?= $row['telefone2'] ?>" readonly>
                </div>

                <div class="campo campo-pessoal">
                    <a href="#" onclick="openWhatsApp()">
                        <img src="assets/img/whatsapp.png" class="whatsapp_btn" alt="WhatsApp" width="300">
                    </a>
                </div>

                <script>
                    function openWhatsApp() {
                        var telefone = document.getElementById("telefone").value;
                        var telefoneLimpo = telefone.replace(/[^\d]/g, ""); // Remover caracteres não numéricos
                        var telefoneCompleto = "55" + 6192066241;
                        var mensagem = "Olá, vi o seu perfil no site Busca Service. Vamos conversar?";
                        var url = "https://api.whatsapp.com/send?phone=" + telefoneCompleto + "&text=" + encodeURIComponent(mensagem);
                        window.open(url, "_blank");
                    }
                </script>
                <!-- <script>
                    function openWhatsApp() {
                        var telefone = document.getElementById("telefone").value;
                        var telefoneLimpo = telefone.replace(/[^\d]/g, ""); // Remover caracteres não numéricos
                        var telefoneCompleto = "55" + telefoneLimpo;
                        var url = "https://api.whatsapp.com/send?phone=" + telefoneCompleto;
                        window.open(url, "_blank");
                    }
                </script> -->
            </div>
        </div>

        <section class="imagens_trabalho">
            <h2 class="imagens_trabalho_titulo">Galeria de imagens</h2>
            <div class="imagens_trabalho_img">
                <?php if (!empty($row['fotosec']) || !empty($row['fotosec2'])) : ?>
                    <?php if (!empty($row['fotosec'])) : ?>
                        <div class="campo campo-pessoal">
                            <img src="<?= $row['fotosec'] ?>" class="imagens_perfil_trabalho" alt="imagem" style="width:230px;height:220px;">
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($row['fotosec2'])) : ?>
                        <div class="campo campo-pessoal">
                            <img src="<?= $row['fotosec2'] ?>" class="imagens_perfil_trabalho" alt="Imagem" style="width:230px;height:220px;">
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="nao-encontrados-img">
                    <p>A galeria de imagens de <?= $row['titulo'] ?> não possui imagens no momento</p>
                     </div>
                <?php endif; ?>
            </div>
        </section>



        <div class="avaliacoes">
            <h3 class="avaliacoes_titulo">Avaliações</h3>

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
                        $starImage = ($i <= $avaliacao['pontuacao']) ? 'assets/img/estrela.png' : 'assets/img/estrela_vazia2.png';
                        echo '<img src="' . $starImage . '" style="width: 25px; height: 25px;">';
                    }
                    echo '</div>';

                    echo '</span>';
                    echo '</div>';
                    echo '<p class="avaliacao-comentario">' . $avaliacao['comentario'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="nao-encontrados">';
                echo '<p>Nenhuma avaliação encontrada</p>';
                echo '</div>';
            }
            ?>

            <!-- Código do formulário de avaliação -->
            <div class="avaliacao-form">
                <h4 class="avaliacao_form_titulo">Fazer uma Avaliação</h4>
                <form action="cadastra_avaliacao.php" method="POST">
                    <div class="campo-avaliacao">
                        <label for="pontuacao" class="label_perfil">Pontuação:</label>
                        <div class="estrelas">
                            <input type="radio" id="pontuacao1" name="pontuacao" value="1" required>
                            <label for="pontuacao1" onclick="marcarEstrelas(1)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="radio" id="pontuacao2" name="pontuacao" value="2" required>
                            <label for="pontuacao2" onclick="marcarEstrelas(2)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="radio" id="pontuacao3" name="pontuacao" value="3" required>
                            <label for="pontuacao3" onclick="marcarEstrelas(3)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="radio" id="pontuacao4" name="pontuacao" value="4" required>
                            <label for="pontuacao4" onclick="marcarEstrelas(4)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="radio" id="pontuacao5" name="pontuacao" value="5" required>
                            <label for="pontuacao5" onclick="marcarEstrelas(5)"><img src="assets/img/estrela_vazia2.png" class="estrelas_ind" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="hidden" name="idpro" value="<?php echo $row['idpro']; ?>">
                        </div>
                    </div>
                    <div class="campo-avaliacao">
                        <label for="comentario" class="label_perfil">Comentário:</label>
                        <textarea id="comentario" name="comentario" class="input_perfil2"></textarea>
                        <input type="hidden" name="idpro" value="<?= $idpro ?>">
                    </div>
                    <input type="submit" value="Enviar Avaliação" class="btn_enviar_ava_perfilpro">
                </form>
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
        </div>
    </div>
    </main>

    <!--INÍCIO DOBRA RODAPÉ-->

    <!-- inclui o arquivo de rodape do site -->
    <?php require_once 'layouts/site/footer.php'; ?>