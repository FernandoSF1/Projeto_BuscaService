<?php
class Conexao
{
    public static function getInstance()
    {
        try {
            $dbh = new PDO(
                "mysql:host=127.0.0.1;port=3306;dbname=busca_service",
                "root",
                ""
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            $dbh->query('SET NAMES utf8');
            $dbh->query('SET CHARACTER SET utf8');

            //echo "Conexão com o banco de dados estabelecida com sucesso!";

            return $dbh;
        } catch (PDOException $error) {
            throw new Exception("Erro ao tentar conectar ao banco de dados. Verifique a conexão e o SGBD.");
        }
    }
}

/* Teste a conexão
try {
    Conexao::getInstance();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>*/
