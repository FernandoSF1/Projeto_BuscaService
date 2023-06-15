<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Diretório de destino das imagens
    $dir = "../../uploads/";

    // Manipulação do campo 'fotosec'
    $fotosec = isset($_FILES['fotosec']) ? $_FILES['fotosec'] : null;
    $destino_fotosec = '';

    if (!empty($fotosec['name'])) {
        $destino_fotosec = $dir . $fotosec['name'];
        move_uploaded_file($fotosec['tmp_name'], $destino_fotosec);
    }
}
