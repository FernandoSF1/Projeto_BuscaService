<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Diretório de destino das imagens
    $dir = "../../uploads/";

    // Manipulação do campo 'fotosec2'
    $fotosec2 = isset($_FILES['fotosec2']) ? $_FILES['fotosec2'] : null;
    $destino_fotosec2 = '';

    if (!empty($fotosec2['name'])) {
        $destino_fotosec2 = $dir . $fotosec2['name'];
        move_uploaded_file($fotosec2['tmp_name'], $destino_fotosec2);
    }
}
