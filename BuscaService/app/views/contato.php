<?php
# para trabalhar com sessões sempre iniciamos com session_start.
session_start();

# inclui os arquivos header, menu e login.
require_once 'layouts/site/header.php';
require_once 'layouts/site/menu.php';
require_once 'login.php';
?>

<main class="bg_form">
  <section class="main_contato">
  <?php require_once "botoes_navegacao.php"?>
    <div class="main_contato_dados">
      <h1>Contato</h1>

      <div class="contato-info">
        <div>
          <div>
            <p>Telefone</p>
            <p><span>(61) 99881-9998</span></p>
          </div>
        </div>

        <div>
          <div>
            <p>E-mail</p>
            <p><span>buscaservice@hotmail.com</span></p>
          </div>
        </div>
      </div>

      <form id="form_contato" method="POST" action="" data-customvalidate="true" autocomplete="off" novalidate>


        <div>
          <div>
            <div>
              <div>
                <label for="contato_nome"><span>*</span>Nome Completo</label>
                <input type="text" id="contato_nome" name="nome" class="form-control" data-name="Nome" required>
              </div>
            </div>

            <div>
              <div>
                <label for="contato_email"><span>*</span>E-mail</label>
                <input type="email" id="contato_email" name="email" class="form-control" data-name="E-mail" required>
              </div>
            </div>
          </div>

          <div>
            <div>
              <div>
                <label for="contato_telefone"><span>*</span>Telefone/Celular</label>
                <input type="text" id="contato_telefone" name="telefone" class="form-control phone" data-name="Telefone" data-mask="telefone" required>
              </div>
            </div>

            <div>
              <div>
                <label for="contato_assunto"><span>*</span>Assunto</label>
                <input type="text" id="contato_assunto" name="assunto" class="form-control" data-name="Assunto" required>
              </div>
            </div>
          </div>

          <div>
            <label for="contato_mensagem"><span>*</span>Mensagem</label>
            <textarea id="contato_mensagem" name="mensagem" class="form-control" data-name="Mensagem" required></textarea>
          </div>

          <div class="alinhar_bt">
            <a href="" class="bt">Enviar Mensagem</a>
          </div>
        </div>
      </form>
    </div>
  </section>

</main>

<!-- inclui o arquivo de rodape do site -->
<?php require_once 'layouts/site/footer.php'; ?>