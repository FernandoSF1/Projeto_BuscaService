<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
?>

<!--INÍCIO DOBRA POLITICA DE AVISO LEGAL-->
<main>
<?php require_once "botoes_navegacao.php"?>
    <section>
        <article class="introducao_link">
            <header>
                <h1>Aviso Legal - BuscaService!</h1>
            </header>
        </article>

        <div class="conteudo_quem">
            <header>
                <h2 class="titulo_link">AVISO LEGAL</h2>
            </header>

            <h3 class="subtitulo">Bem-vindo ao site BUSCA SERVICE!</h3>

            <p>Ele é oferecido como um serviço aos nossos clientes. O
                conteúdo publicado é de propriedade da Busca Service e
                está protegido pelas leis brasileiras e internacionais
                de direitos autorais. Agradecemos o seu interesse em
                nossa empresa e pela visita em nosso site. Para garantir
                a qualidade desse serviço, confira as seguintes
                condições e as regras básicas que regem o uso do mesmo.</p>

            <p>1- O uso do site Busca Service constitui o seu
                conhecimento e aceitação desses termos. O acesso e a
                utilização são para a contratação de serviços oferecidos
                por profissionais autônomos para seus clientes.</p>

            <p>2- Você pode fazer download, copiar ou imprimir os
                elementos e as informações aqui contidas, sem
                modificações e somente para fins meramente informativos,
                não sendo permitida a utilização para fins comerciais.</p>

            <p>3- O Busca Service não pode ser responsabilizada por
                problemas na contratação dos serviços, sendo de
                responsabilidade de quem oferece o serviço.</p>

            <p>4- Este site (compartilhado com meios digitais,
                aplicativos para dispositivos móveis e mídias sociais)
                tem licença para utilizar submissões, ou seja, oferecer
                aos usuários a oportunidade de enviar, publicar ou
                exibir conteúdos próprios, como fotos, imagens, textos,
                dados, opiniões ou notas.</p>

            <p>5- Você será obrigado a se registrar para usar alguns
                recursos do site como cadastrar e contratar serviços.
                Podemos alterar os requisitos de registro sem aviso
                prévio.</p>

            <p>6- Você concorda que será pessoalmente responsável pelo
                uso deste site e por toda a sua comunicação e atividades
                nele. O Busca Service reserva o direito de negar o
                acesso a este site, a qualquer momento sem aviso prévio,
                por envolvimento do usuário em atividades proibidas,
                falta de respeito com outros usuários ou violação de
                outra forma dos termos de serviço.</p>

            <p>7- É de sua responsabilidade ler e familiarizar-se com
                todas as Políticas disponíveis neste site.</p>

            <p>8- Procuramos sempre fornecer informações precisas e
                atualizadas, mas não garantimos e tampouco declaramos,
                expressa ou implicitamente por conta de eventuais
                mudanças físicas e internas, a precisão das mesmas.</p>

            <p>9- O Busca Service se isenta da responsabilidade por
                prejuízos ou danos diretos ou indiretos decorrentes do
                uso ou impossibilidade de uso deste site ou de outros
                conectados a este.</p>

            <p>10- Este Aviso Legal pode ser atualizado a qualquer
                momento, por isso recomendamos o acesso periódico a esta
                página.</p>
        </div>
    </section>

    <!--FIM DOBRA POLITICA DE AVISO LEGAL-->

    <!--INCIIO DOBRA RODAPE-->

    <!-- inclui o arquivo de rodape do site -->
    <?php require_once 'layouts/site/footer.php'; ?>