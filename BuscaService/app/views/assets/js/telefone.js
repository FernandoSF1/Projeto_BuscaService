const inputWhatsapp = document.getElementById("telefone-whatsapp");
inputWhatsapp.addEventListener("keyup", formatarTelefone);

const inputGeral = document.getElementById("telefone-geral");
inputGeral.addEventListener("keyup", formatarTelefone);

function formatarTelefone(e) {
    var v = e.target.value.replace(/\D/g, "");
    v = v.replace(/^(\d\d)(\d)/g, "($1)$2");
    v = v.replace(/(\d{5})(\d)/, "$1-$2");
    e.target.value = v;
}
