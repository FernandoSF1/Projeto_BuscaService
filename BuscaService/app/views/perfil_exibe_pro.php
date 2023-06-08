<?php
// Iniciando a sessão
session_start();

// Incluindo o arquivo de cabeçalho
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
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
} catch (PDOException $e) {
    // Tratar o erro do primeiro fetch
    echo "Erro no primeiro fetch: " . $e->getMessage();
    die(); // Interrompe a execução após exibir a mensagem de erro
}

# destroi a conexao com o banco de dados
$dbh = null;
?>



<?php

// Verifica se existe uma mensagem de sucesso enviada via GET
if (isset($_GET['success'])) {
    $successMessage = $_GET['success'];
    $title = '';

    // Verifica o valor de $_GET['success'] para definir o título correspondente
    if ($successMessage === 'cliente') {
        $title = 'Cadastro de Cliente';
    } elseif ($successMessage === 'profissional') {
        $title = 'Cadastro de Profissional';
    } else {
        $title = 'Sucesso'; // Valor padrão para o título
    }

?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '<?= $title ?>',
            text: '<?= $successMessage ?>',
        })
    </script>
<?php
}
?>

<body>
    <div class="container">
        <h1 class="container_titulo">Perfil do Profissional</h1>

        <div class="dados">
            <div class="dados-pessoais_cli">
                <div class="campo campo-pessoal">
                    <label for="nome" class="label_perfil">Nome:</label>
                    <input type="text" id="nome" class="input_perfil" value="<?= $row['nome'] ?>" readonly>
                </div>

                <div class="campo campo-pessoal">
                    <label for="titulo" class="label_perfil">Título do seu negócio:</label>
                    <input type="text" id="titulo" class="input_perfil" value="<?= $row['titulo'] ?>" readonly>
                </div>

                <div class="campo campo-pessoal">
                    <label for="telefone" class="label_perfil">Celular:</label>
                    <input type="text" id="telefone" class="input_perfil" value="<?= $row['telefone'] ?>" readonly>
                </div>

                <div class="campo campo-pessoal">
                    <label for="telefone2" class="label_perfil">Celular:</label>
                    <input type="text" id="telefone2" class="input_perfil" value="<?= $row['telefone2'] ?>" readonly>
                </div>
            </div>

            <div class="dados dados-endereco">
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







            <!-- Código do formulário de avaliação -->
            <div class="avaliacao-form">
                <h2>Fazer uma Avaliação</h2>
                <form action="cadastra_avaliacao.php" method="POST">
                    <div class="campo-avaliacao">
                        <label for="pontuacao" class="label_perfil">Pontuação:</label>
                        <div class="estrelas">
                            <input type="radio" id="pontuacao1" name="pontuacao" value="1" required>
                            <label for="pontuacao1" onclick="marcarEstrelas(1)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="radio" id="pontuacao2" name="pontuacao" value="2" required>
                            <label for="pontuacao2" onclick="marcarEstrelas(2)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="radio" id="pontuacao3" name="pontuacao" value="3" required>
                            <label for="pontuacao3" onclick="marcarEstrelas(3)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="radio" id="pontuacao4" name="pontuacao" value="4" required>
                            <label for="pontuacao4" onclick="marcarEstrelas(4)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="radio" id="pontuacao5" name="pontuacao" value="5" required>
                            <label for="pontuacao5" onclick="marcarEstrelas(5)"><img src="assets/img/estrela_vazia.png" alt="Estrela" style="width: 25px; height: 25px;"></label>
                            <input type="hidden" name="idpro" value="<?php echo $row['idpro']; ?>">
                        </div>
                    </div>
                    <div class="campo-avaliacao">
                        <label for="comentario" class="label_perfil">Comentário:</label>
                        <textarea id="comentario" name="comentario" class="input_perfil"></textarea>
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
            </script>


        </div>

    </div>

    </main>

    <!--INÍCIO DOBRA RODAPÉ-->

    <!-- inclui o arquivo de rodape do site -->
    <?php require_once 'layouts/site/footer.php'; ?>