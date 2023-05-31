<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
?>

<!--INÍCIO DOBRA POLITICA DE QUEM SOMOS-->
<main>
    <section>
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

                <p>Nosso objetivo principal é ajudar a quem precisa
                    serviços, contudo nosso site oferece apoio tanto
                    para clientes quanto para profissionais.
                    Nosso público alvo é quem está disposto a
                    acrescentar junto conosco.</p>

                <h4 class="titulo_link">Visão</h4>

                <p>Esperamos que futuramente nosso site tenha um amplo
                    crescimento dentro do mercado de trabalho e que seja
                    reconhecido principalmente por pessoas que estejam
                    precisando de serviços autônomos.
                    Além disso, que conquiste espaço internacional por
                    meio da inovação, estando sempre à frente dos
                    demais.</p>

                <h5 class="titulo_link">Valores</h5>

                <p>Parceria, serviço ao cliente, integridade e
                    responsabilidade. Foco em resultados, segurança,
                    eficiência e facilidade. Simplicidade, foco na
                    satisfação do cliente, qualidade e melhoria dos
                    serviços. Acessibilidade e prosperidade para todos.
                    Essas são algumas de nossas crenças, filosofia e
                    princípios que deverão orientar nossos negócios e
                    atividades.
                    Deverão ser a motivação, o direcionamento e o
                    posicionamento para agir com foco nos objetivos e na
                    ética.</p>

            </header>
        </div>
    </section>

    <!--FIM DOBRA POLITICA DE PRIVACIDADE-->

    <!--INCIIO DOBRA RODAPE-->

    <!-- inclui o arquivo de rodape do site -->
    <?php require_once 'layouts/site/footer.php'; ?>