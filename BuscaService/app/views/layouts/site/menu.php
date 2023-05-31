<!--INÍCIO DOBRA CABEÇALHO-->
<section>
    <header class="navbar">
        <div class="main-navbar">
            <a class="main-navbar-logo-nome" href="index.php">
                <img class="imagem" src="assets/img/logo.png" alt="Busca Service" title="Busca Service" width="80">
                <h1 class="nomesite">Busca Service</h1>
            </a>

            <nav>
                <ul class="navmenu">
                    <li><a href="quem_somos.php">Quem somos</a></li>
                    <li><a href="contato.php">Contato</a></li>
                    <?php
                    if (isset($_SESSION['usuario']) && $_SESSION['usuario']['perfil'] == 'ADM') {
                        echo "<li><a href='usuario_admin.php'>Admin</a></li>";
                        echo "<li><a href='logout.php'>Sair</a></li>";
                    } else if (isset($_SESSION['usuario'])) {
                        $nomeCompleto = $_SESSION['usuario']['nome'];
                        $nomeArray = explode(' ', $nomeCompleto);
                        $primeiroNome = $nomeArray[0];

                        if ($_SESSION['usuario']['perfil'] == 'CLI') {
                            echo "<li><a href='perfil_cli.php'>$primeiroNome</a></li>";
                        } elseif ($_SESSION['usuario']['perfil'] == 'PRO') {
                            echo "<li><a href='perfil_pro.php'>$primeiroNome</a></li>";
                        }

                        echo "<li><a href='logout.php'>Sair</a></li>";
                    } else {
                        echo "<li><a href='cadastra_cli.php'>Registre-se</a></li>";
                        echo "<li><a href='#' class='modal-link'>Faça seu login</a>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>
</section>
