<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Diretório de destino das imagens
    $dir = "../../uploads/";

    // Manipulação do campo 'fotoprin'
    $fotoprin = isset($_FILES['fotoprin']) ? $_FILES['fotoprin'] : null;
    $destino_fotoprin = $dir . $fotoprin['name'];

    if (!empty($fotoprin['name'])) {
        move_uploaded_file($fotoprin['tmp_name'], $destino_fotoprin);
    }

    // Manipulação do campo 'fotosec'
    $fotosec = isset($_FILES['fotosec']) ? $_FILES['fotosec'] : null;
    $destino_fotosec = '';

    if (!empty($fotosec['name'])) {
        $destino_fotosec = $dir . $fotosec['name'];
        move_uploaded_file($fotosec['tmp_name'], $destino_fotosec);
    }

    // Manipulação do campo 'fotosec2'
    $fotosec2 = isset($_FILES['fotosec2']) ? $_FILES['fotosec2'] : null;
    $destino_fotosec2 = '';

    if (!empty($fotosec2['name'])) {
        $destino_fotosec2 = $dir . $fotosec2['name'];
        move_uploaded_file($fotosec2['tmp_name'], $destino_fotosec2);
    }
}
