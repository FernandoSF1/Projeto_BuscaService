var checkboxes = document.querySelectorAll('input[type=checkbox][name="servico[]"]');
var maxServicos = 6;

checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        var checkedCount = document.querySelectorAll(
            'input[type=checkbox][name="servico[]"]:checked').length;
        if (checkedCount > maxServicos) {
            checkbox.checked = false;
        }
    });
});