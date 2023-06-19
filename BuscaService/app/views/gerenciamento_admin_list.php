<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';

# verifica se existe sessão de usuario e se ele é administrador.
# se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
# sai da pagina.
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
    header("Location: index.php?error=Usuário não tem permissão para acessar esse recurso");
    exit;
}
?>

<!--DOBRA PALCO PRINCIPAL-->

<!--1ª DOBRA-->
<main>
    <?php require_once 'layouts/admin/menu.php'; ?>
    <!--INÍCIO MENU LATERAL-->
    <section class="main_header_lista">
        <div class="sidebar">
            <nav>
                <ul class="sidemenu">
                    <li class="item_sidebar"><a href="usuario_admin_listcli.php">Clientes</a></li>
                    <li class="item_sidebar"><a href="usuario_admin_listpro.php">Profissionais</a></li>
                    <li class="item_sidebar"><a href="usuario_admin_listserv.php">Serviços</a></li>
                    <li class="item_sidebar"><a href="usuario_admin_listava.php">Avaliações</a></li>
                    <!--<li><a href="#">Avaliações</a></li>-->
                </ul>
            </nav>
        </div>

        <!--FIM MENU LATERAL-->

        <div class="main_stage_lista">
        <?php require_once "botoes_navegacao.php" ?>
            <div class="main_stage_lista_content">

                <article>
                    <header>
                        <h1>Listagem de Dados</h1>
                        <p>Bem-vindo(a) à página de Gerenciamento do Busca Service! Aqui você terá acesso a todas as ferramentas necessárias para alterar e excluir dados do sistema.
                        </p><br>
                        <p>Usando o menu lateral localizado à esquerda da página, você poderá navegar pelas diferentes seções de gerenciamento, que incluem clientes, profissionais, serviços e avaliações. Em cada seção, você encontrará as ferramentas para o gerenciamento daquele aspecto do sistema selecionado.</p>

                    </header>
                </article>
            </div>
        </div>
    </section>

    <!--FIM DOBRA PALCO PRINCIPAL-->

    <!--INICIO DOBRA RODAPE-->
    <?php require_once 'layouts/site/footer.php'; ?>