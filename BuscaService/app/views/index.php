<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';

# verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['servico'])) {
    $nomeServico = $_GET['servico'];
} else {
    $nomeServico = '';
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

<!--FIM DOBRA CABEÇALHO-->

<!--INÍCIO DOBRA BUSCA-->
<main>
    <section>
        <div class="introducao_fundo">
        <?php // Verifica se a sessão de usuário está ativa
            if (isset($_SESSION['usuario'])) {
                require_once "botoes_navegacao.php"; // Inclui os botões de navegação
        }?>
            <article class="introducao">
                <header>
                    <h1>Encontre os serviços que esteja precisando!</h1>
                </header>
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


            <?php
            require_once 'login.php';
            require_once "../database/conexao.php";

            // Cria a variável $dbh que vai receber a conexão com o SGBD e banco de dados.
            $dbh = Conexao::getInstance();

            // Consulta SQL para recuperar as categorias e serviços com profissionais associados
            $query = "SELECT s.categoria, s.nome
          FROM servico s
          INNER JOIN profissional_has_servico ps ON s.idserv = ps.idserv
          GROUP BY s.categoria, s.nome
          ORDER BY s.categoria, s.nome";
            $stmt = $dbh->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <article>
                <header>
                    <div class="busca-categoria-titulo-wrapper">
                        <h2 class="busca-categoria-titulo">Busca por categoria de serviço</h2>
                    </div>
                    <div class="busca-categoria">
                        <div class="busca-categoria-scroll">
                            <ul class="busca-categoria-lista">
                                <?php
                                $servicesByCategory = [];

                                if (empty($rows)) {
                                    echo "<span class='nenhum_servico'>Nenhum serviço cadastrado no sistema.</span>";
                                } else {
                                    foreach ($rows as $row) {
                                        $categoria = $row['categoria'];
                                        $nome = $row['nome'];
                                        $servicesByCategory[$categoria][] = $nome;
                                    }

                                    foreach ($servicesByCategory as $categoria => $servicos) {
                                        echo "<div class='categoria_servicos'>";
                                        echo "<p class='categoria-servico'><b>$categoria:</b></p>";

                                        echo "<ul>";

                                        foreach ($servicos as $nome) {
                                            echo "<li><a href='resultado.php?servico=" . urlencode($nome) . "' class='nome-servico'>$nome</a></li>";
                                        }

                                        echo "</ul>";
                                        echo "</div>";
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </header>
            </article>
        </div>


        <!--FIM DOBRA BUSCA-->

        <!--INÍCIO DOBRA REGISTRO-->
        <article>
    <header>
        <div class="registrar">
        <?php
        if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'PRO' && $_SESSION['usuario']['perfil'] != 'CLI')) {
            // Usuário não logado ou usuário logado com perfil diferente de PRO e CLI, exibe as divs de registro
            echo '
                <div class="registrar-pro">
                    <p>Se você é um profissional autônomo, registre o seu serviço no site e alcance mais clientes!</p>
                    <div class="registrar-pro-btn">
                        <a href="cadastra_pro.php">Registre-se como profissional</a>
                    </div>
                </div>

                <div class="registrar-cli">
                    <p>Você é um cliente? Registre-se como cliente e tenha acesso a mais recursos</p>
                    <div class="registrar-cli-btn">
                        <a href="cadastra_cli.php">Registre-se como cliente</a>
                    </div>
                </div>
            </div>';
        }
        ?>
    </header>
</article>


    </section>
    <!--FIM DOBRA REGISTRO-->

    <!--DOBRA PALCO PRINCIPAL-->


    <!--INICIO SESSÃO SESSÃO DE ARTIGOS-->
    <section class="main_blog">
        <header class="main_blog_header">
            <h1 class="icon-blog">O que é o Busca Service?</h1>
            <p>Encontre o serviço ideal em apenas alguns cliques! O Busca Service conecta você aos profissionais da sua região, enquanto permite que eles alcancem mais clientes e expandam seus negócios. A solução perfeita para quem busca praticidade e eficiência.</p>
        </header>

        <article>
            <a href="#">
                <img src="assets/img/aperto_mao.png" width="50" alt="Imagem post" title="Imagem Post">
            </a>
            <p class="category">O que fazemos?</p>
            <h2>Facilitamos a sua busca por serviços que você precisa por lhe apresentar uma lista de profissionais da área pesquisada.<br>
                Também divulgamos serviços de profissionais autônomos.</a></h2>
        </article>

        <article>
            <a href="#">
                <img src="assets/img/procura.png" width="50" alt="Imagem post" title="Imagem Post">
            </a>
            <p class="category">Como achar um profissional?</a></p>
            <h2>Está precisando de algum serviço?<br>
                Digite-o na pesquisa, procure pelo profissional desejado e entre em contato diretamente com ele pelos telefones disponíveis.</a></h2>
        </article>

        <article>
            <a href="#">
                <img src="assets/img/divulgar.png" width="50" alt="Imagem post" title="Imagem Post">
            </a>
            <p class="category">Como divulgar meu trabalho?</a></p>
            <h2>Você é um profissional autônomo e deseja aumentar a divulgação do seu serviço?<br>
                Registre o seu negócio no site e conquiste mais clientes!</a></h2>
        </article>
    </section>
</main>
<!--FIM SESSÃO SESSÃO DE ARTIGOS-->


<!-- inclui o arquivo de rodape do site -->
<?php require_once 'layouts/site/footer.php'; ?>