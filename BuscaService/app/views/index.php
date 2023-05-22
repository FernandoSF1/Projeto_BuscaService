<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();

    # inclui os arquivos header, menu e login.
    require_once 'layouts/site/header.php';
    require_once 'layouts/site/menu.php';
    require_once 'login.php';
?>

<main>
    <?php
        # verifca se existe uma mensagem de erro enviada via GET.
        # se sim, exibe a mensagem enviada no cabeçalho.
        if(isset($_GET['error'])) { ?>
            <script>
                Swal.fire({
                icon: 'error',
                title: 'Usuários',
                text: '<?=$_GET['error'] ?>',
                })
            </script>
    <?php } ?>

    <!--FIM DOBRA CABEÇALHO-->

    <!--INÍCIO DOBRA BUSCA-->
        <section>
            <article class="introducao">
                <header>
                    <h1>Encontre os serviços que está buscando!</h1>
                </header>
            </article>

            <article>
                <header>
                    <div class="busca">
                        <div class="main-busca">
                            <input type="text" class="busca-txt" placeholder="Pesquisar">
                            <a href="#" class="busca-btn">
                                <img src="assets/img/lupa.png" alt="Lupa" width="25"><br><br><br>
                            </a>
                        </div>
                    </div>
                </header>
            </article>

            <article>
                <header>
                    <div class="busca-categoria">
                        <h2>Busca por categoria de serviço</h2>
                        <ul class="busca-categoria-lista">
                            <li><a href="#">Saúde</a></li>
                            <li><a href="#">Reformas</a></li>
                            <li><a href="#">Manutenção</a></li>
                            <li><a href="#">Domésticos</a></li>
                        </ul>
                    </div>
                </header>
            </article>
        <!--FIM DOBRA BUSCA-->

        <!--INÍCIO DOBRA REGISTRO-->
            <article>
                <header>
                    <div class="registrar">
                        <div class="registrar-pro">
                            <p>Se você é um profissional autônomo, registre o seu serviço no site e alcançe mais clientes!</p>
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
                    </div>
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
                <p><a href="" class="category">O que fazemos?</a></p>
                <h2><a href="" class="title">Facilitamos a sua busca por serviços que você precisa por lhe apresentar uma lista de profissionais da área pesquisada.<br>
                    Também divulgamos serviços de profissionais autônomos.</a></h2>
            </article>

            <article>
                <a href="#">
                    <img src="assets/img/procura.png" width="50" alt="Imagem post" title="Imagem Post">
                </a>
                <p><a href="" class="category">Como achar um profissional?</a></p>
                <h2><a href="" class="title">Está precisando de algum serviço?<br>
                    Digite-o na pesquisa, procure pelo profissional desejado e entre em contato diretamente com ele pelos telefones disponíveis.</a></h2>
            </article>

            <article>
                <a href="#">
                    <img src="assets/img/divulgar.png" width="50" alt="Imagem post" title="Imagem Post">
                </a>
                <p><a href="" class="category">Como divulgar meu trabalho?</a></p>
                <h2><a href="" class="title">Você é um profissional autônomo e deseja aumentar a divulgação do seu serviço?<br>
                    Registre o seu negócio no site e conquiste mais clientes!</a></h2>
            </article>
        </section>

        <!--FIM SESSÃO SESSÃO DE ARTIGOS-->
</main>
       
        <!-- inclui o arquivo de rodape do site -->
        <?php require_once 'layouts/site/footer.php'; ?>