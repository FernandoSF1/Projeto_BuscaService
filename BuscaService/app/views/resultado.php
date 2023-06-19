<?php

session_start();


require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
require_once "../database/conexao.php";

// Captura o valor pesquisado pelo usuário
$nomeServico = isset($_GET['servico']) ? $_GET['servico'] : '';

# cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
$dbh = Conexao::getInstance();

// Consulta SQL para recuperar os profissionais que oferecem o serviço pesquisado
$query = "SELECT DISTINCT p.* FROM profissional p
          INNER JOIN profissional_has_servico ps ON p.idpro = ps.idpro
          INNER JOIN servico s ON ps.idserv = s.idserv
          LEFT JOIN avaliacao a ON p.idpro = a.idpro
          WHERE s.nome LIKE :nomeServico AND p.status = 1
          ORDER BY a.pontuacao DESC";

$stmt = $dbh->prepare($query);
$stmt->bindValue(':nomeServico', '%' . $nomeServico . '%');
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo '<pre>';var_dump($rows);exit;
?>

<main>

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
  
  <article class="main_volta">
    <?php require_once "botoes_navegacao.php"?>
  </article>
  <article>
    <header>
      <div class="busca">
        <div>
          <form action="resultado.php" method="get" class="main-busca">
            <input type="text" name="servico" class="busca-txt" placeholder="Pesquisar">
            <button type="submit" class="busca-btn">
              <img src="assets/img/lupa.png" alt="Lupa" width="25">
            </button>
          </form>
        </div>
      </div>
    </header>
  </article>

  <article>
    <header>
      <?php if (count($rows) > 0) : ?>
        <div class="encontrados">
          <p>Encontramos <span><?php echo count($rows); ?></span> profissionais para serviços de <span><?php echo $nomeServico; ?></span></p>
        </div>
      <?php else : ?>
        <div class="nao-encontrados">
          <p>Não encontramos profissionais que ofereçam o serviço de <span><?php echo $nomeServico; ?></p>
        </div>
      <?php endif; ?>
    </header>
  </article>

  <section class="resultados">
    <?php foreach ($rows as $row) : ?>
      <div class="card">
        <div class="card-content">
          <div class="card-left">
            <img src="<?= $row['fotoprin'] ?>" alt="Imagem perfil" style="width:160px;height:150px;">
            <h2><?php echo $row['titulo']; ?></h2>
            <p>Localidade: <?php echo $row['estado'] . ', ' . $row['cidade'] . ' - ' . $row['bairro']; ?></p>

          </div>
          <div class="card-right">
            <p>
              <span class="destaque">Serviços oferecidos:</span><br>
              <?php
              // Consulta SQL para recuperar os serviços oferecidos pelo profissional
              $queryServicos = "SELECT s.nome FROM servico s
                                INNER JOIN profissional_has_servico ps ON s.idserv = ps.idserv
                                WHERE ps.idpro = :idpro
                                ORDER BY s.nome = :nomeServico DESC, s.nome ASC";

              $stmtServicos = $dbh->prepare($queryServicos);
              $stmtServicos->bindValue(':idpro', $row['idpro']);
              $stmtServicos->bindValue(':nomeServico', $nomeServico);
              $stmtServicos->execute();
              $servicos = $stmtServicos->fetchAll(PDO::FETCH_COLUMN);

              echo implode(', ', $servicos);
              ?>
            </p>
            <p>
              <span class="destaque">Nota:</span>
              <?php
              // Consulta SQL para calcular a média da pontuação do profissional
              $queryPontuacao = "SELECT AVG(pontuacao) as media_pontuacao, COUNT(*) as total_avaliacoes FROM avaliacao WHERE idpro = :idpro";

              $stmtPontuacao = $dbh->prepare($queryPontuacao);
              $stmtPontuacao->bindValue(':idpro', $row['idpro']);
              $stmtPontuacao->execute();
              $mediaPontuacao = $stmtPontuacao->fetch(PDO::FETCH_ASSOC);

              $pontuacaoInteira = floor($mediaPontuacao['media_pontuacao']);
              $pontuacaoDecimal = $mediaPontuacao['media_pontuacao'] - $pontuacaoInteira;

              // Exibir o número da média da pontuação
              echo number_format($mediaPontuacao['media_pontuacao'], 1);
              ?>
              (<?php echo $mediaPontuacao['total_avaliacoes']; ?> avaliações)
            </p>
            <p>
              <?php
              // Exibir estrelas inteiras
              for ($i = 0; $i < $pontuacaoInteira; $i++) {
                echo '<img src="assets/img/estrela.png" alt="Estrela" style="width: 25px; height: 25px;">';
              }

              // Exibir meia estrela, se houver
              if ($pontuacaoDecimal >= 0.5) {
                echo '<img src="assets/img/meia_estrela.png" alt="Meia Estrela" style="width: 25px; height: 25px;">';
              }

              // Exibir estrelas vazias para completar
              $totalEstrelas = 5; // Total de estrelas
              $estrelasVazias = $totalEstrelas - $pontuacaoInteira - ($pontuacaoDecimal >= 0.5 ? 1 : 0);
              for ($i = 0; $i < $estrelasVazias; $i++) {
                echo '<img src="assets/img/estrela_vazia.png" alt="Estrela Vazia" style="width: 25px; height: 25px;">';
              }
              ?>
            </p>

            <!-- Formulário para enviar o ID do profissional -->
            <form action="perfil_exibe_pro.php" method="get">
              <?php
              $idpro_encrypted = base64_encode($row['idpro']);
              ?>
              <input type="hidden" name="idpro" value="<?php echo $idpro_encrypted; ?>">
              <button type="submit" class="card-button">Ver Perfil</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </section>



</main>

<?php require_once 'layouts/site/footer.php'; ?>