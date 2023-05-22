<!--INÍCIO DOBRA CABEÇALHO-->
<section>
    <header class="navbar">
        <div class="main-navbar">
            <a class="main-navbar-logo-nome" href="index.php">
                <img class="imagem "src="assets/img/logo.png" alt="Busca Service" title="Busca Service" width="80">
            
            <h1 class="nomesite">Busca Service</h1>
            </a>

            <nav>
                <ul class="navmenu">
                    <li><a href="quem_somos.html">Quem somos</a></li>
                    <li><a href="cadastra_cli.php">Registre-se</a></li>
                    <li><a href="contato.html">Contato</a></li>
                    
                    <?php
                    # verifica se existe sessão de usuario e se ele é administrador.
                    # se não for o primeiro caso, verifica se a sessao existe.
                    # por ultimo adiciona somente o link para o login se a sessão não existir. 
                    if (isset($_SESSION['usuario']) && $_SESSION['usuario']['perfil'] == 'ADM' )  {
                        echo "<li><a href='usuario_admin.php'>Admin</a></li>";
                        echo "<li><a href='logout.php'>Sair</a></li>";
                    } else if(isset($_SESSION['usuario'])) {
                        echo "<li><a href='#'>Meu perfil</a></li>";
                        echo "<li><a href='logout.php'>Sair</a></li>";
                    } else {
                        echo "<li><a href='#' class='modal-link'>Faça seu login</a>";                
                    }
                    ?>
                    
                </ul>
            </nav>
        </div>
    </header>
</section>
