const fileInputs = document.querySelectorAll(".fileInput");
const nomeArquivosSelecionados = document.querySelectorAll("[id^=arquivo_selecionado]");

fileInputs.forEach((fileInput, index) => {
  fileInput.addEventListener("change", function () {
    if (fileInput.files.length > 0) {
      nomeArquivosSelecionados[index].textContent = fileInput.files[0].name;
    } else {
      nomeArquivosSelecionados[index].textContent = "";
    }
  });
});
