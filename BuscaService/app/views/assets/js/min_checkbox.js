document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('formulario_img');
    var servicoInputs = document.querySelectorAll('input[name="servico[]"]');
    var mensagemAviso = document.querySelector('.servicos-obrigatorio');

    form.addEventListener('submit', function(event) {
        var servicoSelecionado = false;
        for (var i = 0; i < servicoInputs.length; i++) {
            if (servicoInputs[i].checked) {
                servicoSelecionado = true;
                break;
            }
        }
        if (!servicoSelecionado) {
            event.preventDefault();
            mensagemAviso.style.display = 'block';
        } else {
            mensagemAviso.style.display = 'none';
        }
    });

    for (var i = 0; i < servicoInputs.length; i++) {
        servicoInputs[i].addEventListener('change', function() {
            if (this.checked) {
                mensagemAviso.style.display = 'none';
            }
        });
    }
});

