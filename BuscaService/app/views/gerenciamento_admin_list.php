<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();

    # inclui os arquivos header, menu e login.
    require_once 'layouts/site/header.php';
    require_once 'layouts/site/menu.php';
?>


    <!--DOBRA PALCO PRINCIPAL-->

    <!--1ª DOBRA-->
    <main>

        <!--INÍCIO MENU LATERAL-->
        <section class="main_header_lista">
            <div class="sidebar">
            <nav>
                <ul class="sidemenu">
                <li class="item_sidebar"><a href="usuario_admin_listcli.php">Clientes</a></li>
                <li class="item_sidebar"><a href="usuario_admin_listpro.php">Profissionais</a></li>
                <li class="item_sidebar"><a href="usuario_admin_listserv.php">Serviços</a></li>
                <li class="item_sidebar"><a href="usuario_admin_addserv.php">Pagamentos</a></li>
                <!--<li><a href="#">Avaliações</a></li>-->
                </ul>
            </nav>
            </div>
  
        <!--FIM MENU LATERAL-->

        <div class="main_stage_lista">
            <div class="main_stage_lista_content">
               
                <article>
                    <header>
                            <h1>Listagem de Dados</h1>
                            <p>Bem-vindo(a) à página de Gerenciamento do Busca Service! Aqui você terá acesso a todas as ferramentas necessárias para alterar e excluir dados do sistema.
                            </p><br>
                            <p>Usando o menu lateral localizado à esquerda da página, você poderá navegar pelas diferentes seções de gerenciamento, que incluem clientes, profissionais e serviços. Em cada seção, você encontrará as ferramentas para o gerenciamento daquele aspecto do sistema selecionado.</p>
                        
                    </header>
               </article>
               <article>
                    <header>
                        <h2><a href="usuario_admin.php" class="btn_volta">Voltar</a></h2>
                    </header>
                </article>
               
            </div>
        </div>
        </section>

        <!--FIM DOBRA PALCO PRINCIPAL-->

        <!--INICIO DOBRA RODAPE-->
  <?php require_once 'layouts/site/footer.php'; ?>