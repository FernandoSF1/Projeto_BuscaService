<!--INCIIO DOBRA RODAPE-->

<section class="main_footer">
        <header>
            <h1>Quer saber mais?</h1>
        </header>
        <article class="main_footer_our_pages">
            <header>
                <h2>Nossas Páginas</h2>
            </header>
            <ul>
                <li><a href="index.php">Topo</a></li>
                <li><a href="quem_somos.html">Quem somos</a></li>
            </ul>
        </article>

        <article class="main_footer_links">
            <header>
                <h2>Links Úteis</h2>
            </header>
            <ul>
                <li><a href="politica_privacidade.html">Política de Privacidade</a></li>
                <li><a href="aviso_legal.html">Aviso Legal</a></li>
                <li><a href="termos_uso.html">Termos de Uso</a></li>
            </ul>
        </article>

        <article class="main_footer_about">
            <header>
                <h2>Sobre o Projeto</h2>
            </header>
            <p>Proporcionando maior conforto aos usuários na busca por serviços essenciais, ao mesmo tempo em que encurta a distância entre profissionais qualificados e seus potenciais clientes</p>
        </article>
    </section>

    <footer class="main_footer_rights">
        <p> BUSCA SERVICE - Todos os direitos reservados. &reg;</p>
    </footer>
    <!--FIM DOBRA RODAPE-->
    
</body>
<script>
   		// Seleciona o link e a janela modal
		var link = document.querySelector('.modal-link');
		var modal = document.querySelector('.modal');
		var overlay = document.querySelector('.overlay');

		// Adiciona um listener de evento para o link
		link.addEventListener('click', function(event) {
			event.preventDefault(); // previne o comportamento padrão do link (navegar para outra página)

			overlay.style.display = 'block'; // exibe a camada escura
			modal.style.display = 'block'; // exibe a janela modal
		});

		// Adiciona um listener de evento para a camada escura
		overlay.addEventListener('click', function() {
			overlay.style.display = 'none'; // oculta a camada escura
			modal.style.display = 'none'; // oculta a janela modal
		});
</script>
</html>