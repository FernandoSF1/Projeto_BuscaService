<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui o arquivo header
require_once 'layouts/site/header.php';

# verifica se existe sessão de usuario e se ele é administrador.
# se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
# sai da pagina.
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
    header("Location: index.php?error=Você não tem permissão para acessar esse recurso");
    exit;
}
?>

<body>
    <?php require_once 'layouts/admin/menu.php'; ?>
    <main>
    <?php require_once "botoes_navegacao.php"?>
        <?php
        # Verifica se existe uma mensagem de erro enviada via GET
        if (isset($_GET['error'])) {
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ops!',
                    text: '<?= $_GET['error'] ?>',
                });
            </script>
        <?php
        }
        # Verifica se existe uma mensagem de sucesso enviada via GET
        elseif (isset($_GET['success'])) {
        ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: '<?= $_GET['success'] ?>',
                });
            </script>
        <?php
        }
        ?>
        <div class="main_opc">

            <section class="main_course" id="escola">
                <header class="main_course_header">

                </header>
                <div class="main_course_content">
                    <article>
                        <h2 align="center">Cadastrar dados</h2>
                        <header>

                            <p align="center">
                                <a href="gerenciamento_admin_add.php"><img src="assets/img/cadastrar_dados.png" width="200"></a>
                            </p>

                        </header>
                    </article>
                    <article>
                        <h2 align="center">Listar dados</h2>
                        <header>

                            <p align="center"><a href="gerenciamento_admin_list.php"><img src="assets/img/listar_dados.png" width="200"></a></p>

                        </header>
                    </article>

                </div>
                </article>
            </section>
        </div>

    </main>
    <!--FIM DOBRA PALCO PRINCIPAL-->

</body>


</html>