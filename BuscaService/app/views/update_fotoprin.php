<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Diretório de destino das imagens
    $dir = "../../uploads/";

    // Manipulação do campo 'fotoprin'
    $fotoprin = isset($_FILES['fotoprin']) ? $_FILES['fotoprin'] : null;
    $destino_fotoprin = '';

    if (!empty($fotoprin['name'])) {
        $destino_fotoprin = $dir . $fotoprin['name'];
        move_uploaded_file($fotoprin['tmp_name'], $destino_fotoprin);
    }
}
