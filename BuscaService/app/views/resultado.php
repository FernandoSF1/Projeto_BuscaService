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
          WHERE s.nome LIKE :nomeServico";

$stmt = $dbh->prepare($query);
$stmt->bindValue(':nomeServico', '%' . $nomeServico . '%');
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
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
    <?php if (count($rows) > 0): ?>
    <div class="encontrados">
        <p>Encontramos <span><?php echo count($rows); ?></span> profissionais para serviços de <span><?php echo $nomeServico; ?></span> em <span>(bairro, cidade - estado)</span></p>
    </div>
<?php else: ?>
    <div class="nao-encontrados">
        <p>Não encontramos profissionais que ofereçam o serviço de <span><?php echo $nomeServico; ?></span> em <span>(bairro, cidade - estado)</span></p>
    </div>
<?php endif; ?>
    </header>
  </article>

  <section class="resultados">
    <?php foreach ($rows as $row): ?>
      <div class="card">
        <div class="card-content">
          <div class="card-left">
            <img src="perfil.jpg" alt="Foto do Perfil"> <!-- Substitua 'perfil.jpg' pelo caminho correto da imagem do perfil -->
            <h2><?php echo $row['titulo']; ?></h2>
            <p>Localidade: <?php echo $row['estado'] . ', ' . $row['cidade'] . ' - ' . $row['bairro']; ?></p>
            <a href="#">Ver Perfil</a>
          </div>
          <div class="card-right">
            <p>Serviços oferecidos:
              <?php
                // Consulta SQL para recuperar os serviços oferecidos pelo profissional
                $queryServicos = "SELECT s.nome FROM servico s
                                  INNER JOIN profissional_has_servico ps ON s.idserv = ps.idserv
                                  WHERE ps.idpro = :idProfissional
                                  ORDER BY s.nome = :nomeServico DESC, s.nome ASC";

                $stmtServicos = $dbh->prepare($queryServicos);
                $stmtServicos->bindValue(':idProfissional', $row['idpro']);
                $stmtServicos->bindValue(':nomeServico', $nomeServico);
                $stmtServicos->execute();
                $servicos = $stmtServicos->fetchAll(PDO::FETCH_COLUMN);

                echo implode(', ', $servicos);
              ?>
            </p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

</main>

<?php require_once 'layouts/site/footer.php'; ?>
