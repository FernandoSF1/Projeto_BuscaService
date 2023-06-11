<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
# envio de imagens

$fotoprin = isset($_FILES['fotoprin']) ? $_FILES['fotoprin'] : null;
//upload do arquivo antes
$dir = "../../uploads/";
//recebendo o arquivo multipart
$file = $_FILES["fotoprin"];
// variável com a url de destino da imagem
$destino_fotoprin = "$dir" . $file["name"];
move_uploaded_file($file["tmp_name"], $destino_fotoprin);

$fotosec = isset($_FILES['fotosec']) ? $_FILES['fotosec'] : null;
// Se o campo 'fotosec' estiver vazio, atribui um valor em branco ('')
if (empty($fotosec['name'])) {
$destino_fotosec = '';
} else {
$dir = "../../uploads/";
$file = $_FILES["fotosec"];
$destino_fotosec = "$dir" . $file["name"];
move_uploaded_file($file["tmp_name"], $destino_fotosec);
}

$fotosec2 = isset($_FILES['fotosec2']) ? $_FILES['fotosec2'] : null;
// Se o campo 'fotosec2' estiver vazio, atribui um valor em branco ('')
if (empty($fotosec2['name'])) {
$destino_fotosec2 = '';
} else {
$dir = "../../uploads/";
$file = $_FILES["fotosec2"];
$destino_fotosec2 = "$dir" . $file["name"];
move_uploaded_file($file["tmp_name"], $destino_fotosec2);
}
}
