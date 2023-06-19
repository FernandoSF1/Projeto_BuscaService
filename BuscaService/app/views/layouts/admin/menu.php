
    <!--INÍCIO DOBRA CABEÇALHO-->
    <section>
        <header class="navbar">
        <div class="main-navbar">
                <a class="main-navbar-logo-nome" href="index.php">
                    <img class="imagem " src="assets/img/logomarca20_bs.png" alt="Busca Service" title="Busca Service" width="350">
                </a>

                <nav>
    <ul class="navmenu">
        <li><a href="index.php" class='navmenu_index'>Início</a></li>

        <?php
        # verifica se existe sessão de usuário e se ele é administrador.
        # se não for o primeiro caso, verifica se a sessão existe.
        # por último, adiciona somente o link para o login se a sessão não existir.
        if (isset($_SESSION['usuario']) && $_SESSION['usuario']['perfil'] == 'ADM') {
            echo "<li><a href='usuario_admin.php' class='navmenu_index'>Menu</a></li>";
            if (isset($_SESSION['usuario']['nome'])) {
                $nomeCompleto = $_SESSION['usuario']['nome'];
                $nomeArray = explode(' ', $nomeCompleto);
                $primeiroNome = $nomeArray[0];
            } else {
                $primeiroNome = '';
            }
            echo "<li><a href='usuario_admin.php' class='navmenu_index'>Bem-vindo, <span class='nome_usu'>$primeiroNome</span></a></li>";
            echo "<li><a href='logout.php' class='navmenu_index'>Sair</a></li>";
        }
        ?>
    </ul>
</nav>
            </div>
        </header>
    </section>