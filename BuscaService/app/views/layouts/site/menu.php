<!--INÍCIO DOBRA CABEÇALHO-->
<section>
    <header class="navbar">
        <div class="main-navbar">
            <a class="main-navbar-logo-nome" href="index.php">
                <img class="imagem" src="assets/img/logomarca20_bs.png" alt="Busca Service" title="Busca Service" width="350">
            </a>

            <nav>
                <ul class="navmenu">
                    <?php
                        $currentPage = basename($_SERVER['PHP_SELF']); // Obtém o nome do arquivo atual
                        if ($currentPage !== 'index.php') {
                            echo '<li><a href="index.php" class="navmenu_index">Início</a></li>';
                        }
                    ?>
                    <li><a href="quem_somos.php" class="navmenu_index">Quem somos</a></li>
                    <li><a href="contato.php" class="navmenu_index">Contato</a></li>
                    <?php
                    if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['perfil']) && $_SESSION['usuario']['perfil'] == 'ADM') {
                        echo "<li><a href='usuario_admin.php' class='navmenu_index'>Admin</a></li>";
                        echo "<li><a href='logout.php' class='navmenu_index'>Sair</a></li>";
                    } else if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['perfil'])) {
                        if (isset($_SESSION['usuario']['nome'])) {
                            $nomeCliente = isset($_SESSION['usuario']['nome']) ? $_SESSION['usuario']['nome'] : '';
                            $nomeArray = explode(' ', $nomeCliente);
                            $primeiroNome = $nomeArray[0];
                        } else {
                            $primeiroNome = '';
                        }

                        if ($_SESSION['usuario']['perfil'] == 'CLI') {
                            echo "<li><a href='perfil_cli.php' class='navmenu_index'>Bem-vindo(a), <span class='nome_usu'>$primeiroNome</span></a></li>";
                        } elseif ($_SESSION['usuario']['perfil'] == 'PRO') {
                            echo "<li><a href='perfil_pro.php' class='navmenu_index'>Bem-vindo(a), <span class='nome_usu'>$primeiroNome</span></a></li>";
                        }

                        echo "<li><a href='logout.php' class='navmenu_index'>Sair</a></li>";
                    } else {
                        echo "<li class='dropdown'>";
                        echo "<span class='navmenu_index dropdown-toggle'>Registre-se</span>";
                        echo "<ul class='dropdown-menu absolute'>";
                        echo "<li class='dropdown-option'><a href='cadastra_cli.php'>Como cliente</a></li>";
                        echo "<li class='dropdown-option'><a href='cadastra_pro.php'>Como profissional</a></li>";
                        echo "</ul>";
                        echo "</li>";
                    
                        // Verifica se a URL atual não é "cadastra_cli.php" nem "cadastra_pro.php"
                        $currentPage = basename($_SERVER['PHP_SELF']);
                        if ($currentPage !== 'cadastra_cli.php' && $currentPage !== 'cadastra_pro.php') {
                            echo "<li><a href='#' class='navmenu_index modal-link'>Faça seu login</a></li>";
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>
</section>
<script>
    $(document).ready(function() {
        $('.dropdown-toggle').click(function(e) {
            e.preventDefault();
            $(this).next('.dropdown-menu').slideToggle('fast');
        });
    });
</script>