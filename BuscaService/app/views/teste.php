$idpro = (isset($_POST['idpro']) ? $_POST['idpro'] : 0);

$query = "ALTER TABLE `busca_service`.`profissional` DROP COLUMN `nome` WHERE idpro = :idpro";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':idpro', $idpro);

# Executa a consulta no banco de dados para excluir a coluna.
$stmt->execute();
