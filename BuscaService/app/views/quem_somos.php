<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
?>

<!--INÍCIO DOBRA POLITICA DE QUEM SOMOS-->
<main class="bg_form">
<?php require_once "botoes_navegacao.php"?>
    <section class="main_contato_dados">
        <article>
            <header class="introducao_link">
                <h1>Quem Somos - Busca Service</h1>
            </header>
        </article>

        <div class="conteudo_quem">
            <header>
                <h2 class="titulo_link">Nosso Negócio</h2>

                <p>Trata-se de um site que visa facilitar a busca por
                    quaisquer serviços de que o cliente precisa,
                    divulgando o trabalho de profissionais das mais
                    diversas áreas.</p>

                <h3 class="titulo_link">Missão</h3>

                <p>Divulgar os melhores profissionais, inicialmente do Distrito Federal e futuramente do Brasil, capacitados e especializados em diversos serviços com ampla experiência e com foco na qualidade e eficiência dos serviços.</p>

                <h4 class="titulo_link">Visão</h4>

                <p>Conquistar espaço no ramo de prestação de serviço oferecendo um site acessível e dinâmico, proporcionando as melhores experiências e facilitando o acesso para os nossos clientes.</p>

                <h5 class="titulo_link">Valores</h5>

                <p>Parceria;<br>
                    Serviço ao cliente;<br>
                    Integridade e responsabilidade;<br>
                    Foco em resultados;<br>
                    Segurança, eficiência e facilidade;<br>
                    Simplicidade, foco na satisfação do cliente;<br>
                    Qualidade e melhoria dos serviços;<br>
                    Acessibilidade para todos.
                </p>

            </header>
        </div>
    </section>

    <!--FIM DOBRA POLITICA DE PRIVACIDADE-->

    <!--INCIIO DOBRA RODAPE-->

    <!-- inclui o arquivo de rodape do site -->
    <?php require_once 'layouts/site/footer.php'; ?>